# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-03

### Added

- Initial release of JR Auto Resizer plugin for Joomla 5
- Automatic resizing of Intro and Full Article images on article save
- WebP conversion for all processed images
- Configurable maximum widths for:
  - Intro images (default: 400px)
  - Intro small images (default: 200px)
  - Full article images (default: 800px)
  - Full article small images (default: 400px)
- Configurable WebP quality (0-100, default: 80)
- Option to keep or delete original files after optimization
- Generation of small image variants for responsive srcset support
- Suffix deduplication to prevent filename issues on re-saves
- Error logging for debugging
- English language files included

[1.0.0]: https://github.com/mabdelaziz77/joomla-jr-auto-resizer/releases/tag/v1.0.0
