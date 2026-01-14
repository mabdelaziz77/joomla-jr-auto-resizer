<?php
/**
 * JR Auto Resizer Plugin
 *
 * @package     Joomreem.Plugin
 * @subpackage  Content.JrAutoResizer
 * @link        https://www.joomreem.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   Copyright (C) 2025 JoomReem. All rights reserved.
 */

namespace Joomreem\Plugin\Content\JrAutoResizer\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Image\Image;
use Joomla\Filesystem\File;

final class JrAutoResizer extends CMSPlugin implements SubscriberInterface
{
    protected $autoloadLanguage = true;

    public static function getSubscribedEvents(): array
    {
        return [
            'onContentBeforeSave' => 'onContentBeforeSave',
        ];
    }

    public function onContentBeforeSave($event)
    {
        $context = $event->getArgument(0);
        $article = $event->getArgument(1);

        // Check context
        if ($context !== 'com_content.article') {
            return;
        }

        // Decode images JSON
        if (empty($article->images)) {
            return;
        }

        $images = json_decode($article->images);

        if (!is_object($images)) {
            return;
        }

        $maxWidthIntro      = (int) $this->params->get('max_width_intro', 400);
        $maxWidthIntroSmall = (int) $this->params->get('max_width_intro_small', 200);
        $maxWidthFull       = (int) $this->params->get('max_width_full', 800);
        $maxWidthFullSmall  = (int) $this->params->get('max_width_full_small', 400);
        $quality            = (int) $this->params->get('quality', 80);
        $keepOriginal       = (bool) $this->params->get('keep_original', 0);

        $modified = false;
        $sameImage = (!empty($images->image_intro) && !empty($images->image_fulltext) && $images->image_intro === $images->image_fulltext);

        // Process Intro Image
        if (!empty($images->image_intro)) {
            $originalIntro = $images->image_intro; // Capture original path

            // Force keep original for the main intro pass if we need it for small intro or full image
            $keepOriginalIntro = true; 
            
            // 1. Main Intro
            $newPath = $this->processImage($originalIntro, $maxWidthIntro, $quality, $keepOriginalIntro, '_intro');
            if ($newPath) {
                $images->image_intro = $newPath;
                $modified = true;
            }

            // 2. Small Intro
            // Only delete original if NOT same image and NOT keeping original requested
            $keepOriginalIntroSmall = $sameImage ? true : $keepOriginal;
            $this->processImage($originalIntro, $maxWidthIntroSmall, $quality, $keepOriginalIntroSmall, '_intro_small');
        }

        // Process Full Article Image
        if (!empty($images->image_fulltext)) {
            $originalFull = $images->image_fulltext; // Capture original path

            // Force keep original for main full pass if we need it for small full
            $keepOriginalFull = true;

            // 3. Main Full
            $newPath = $this->processImage($originalFull, $maxWidthFull, $quality, $keepOriginalFull, '_full');
            if ($newPath) {
                $images->image_fulltext = $newPath;
                $modified = true;
            }

            // 4. Small Full
            // This is the last usage, so we can respect the global keepOriginal setting
            $this->processImage($originalFull, $maxWidthFullSmall, $quality, $keepOriginal, '_full_small');
        }

        if ($modified) {
            $article->images = json_encode($images);
        }
    }

    private function processImage(string $imagePath, int $maxWidth, int $quality, bool $keepOriginal, string $suffix): ?string
    {
        try {
            // Clean path
            $cleanPath = parse_url($imagePath, PHP_URL_PATH);
            $rootPath = JPATH_ROOT . '/' . ltrim($cleanPath, '/');

            if (!File::exists($rootPath)) {
                return null;
            }

            $image = new Image($rootPath);
            $width = $image->getWidth();
            $properties = $image->getImageFileProperties($rootPath);
            $mime = $properties->mime;

            // Resize if needed
            if ($width > $maxWidth) {
                $image->resize($maxWidth, 0, false, Image::SCALE_INSIDE);
            }

            // Generate new path with suffix
            $pathInfo = pathinfo($rootPath);
            $filename = $pathInfo['filename'];
            
            // Strip known suffixes to avoid duplication
            // We check for _intro_small and _full_small first to handle them correctly if they ever appear as input
            $knownSuffixes = ['_intro_small', '_full_small', '_intro', '_full']; 
            foreach ($knownSuffixes as $s) {
                if (substr($filename, -strlen($s)) === $s) {
                    $filename = substr($filename, 0, -strlen($s));
                    break; 
                }
            }

            $newFileName = $filename . $suffix . '.webp';
            $newFilePath = $pathInfo['dirname'] . '/' . $newFileName;
            
            // Optimization: If source is same as target and target exists, return early
            if ($rootPath === $newFilePath && File::exists($newFilePath)) {
                 return str_replace(JPATH_ROOT . '/', '', $newFilePath);
            }

            // Save WebP
            $image->toFile($newFilePath, IMAGETYPE_WEBP, ['quality' => $quality]);

            // Delete original if requested and different from new file
            // Only if original was NOT a generated file (heuristic: simple check)
            if (!$keepOriginal && $rootPath !== $newFilePath) {
                File::delete($rootPath);
            }

            // Return relative path
            return str_replace(JPATH_ROOT . '/', '', $newFilePath);

        } catch (\Throwable $e) {
            // Log error silently
            Log::add('JR Auto Resizer Error: ' . $e->getMessage(), Log::ERROR, 'jrautoresizer');
            return null;
        }
    }
}
