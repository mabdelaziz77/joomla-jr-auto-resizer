# JR Auto Resizer - Joomla 5 Plugin

![Joomla](https://img.shields.io/badge/Joomla-5.x-blue?logo=joomla)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple?logo=php)
![License](https://img.shields.io/badge/License-GPLv2+-green)
![Version](https://img.shields.io/badge/Version-1.0.0-orange)

A Joomla 5 content plugin that **automatically resizes and converts article images to WebP format** when saving articles. Improve your website's performance and PageSpeed scores with optimized images!

## ✨ Features

- 🖼️ **Automatic Image Resizing** - Resizes Intro and Full Article images on save
- 🌐 **WebP Conversion** - Converts images to modern WebP format for better compression
- 📐 **Multiple Sizes** - Generates both standard and small versions for responsive srcset
- ⚡ **Performance Boost** - Smaller file sizes = faster page loads
- 🎛️ **Configurable** - Set custom dimensions and quality for each image type
- 🗑️ **Optional Cleanup** - Option to delete original files after optimization

## 📋 Requirements

- Joomla 5.0 or higher
- PHP 8.1 or higher
- GD Library with WebP support

## 📦 Installation

1. Download the latest release ZIP file
2. In Joomla Admin, go to **System → Install → Extensions**
3. Upload and install the plugin package
4. Go to **System → Manage → Plugins**
5. Search for "JR Auto Resizer" and enable it

## ⚙️ Configuration

Navigate to **System → Manage → Plugins → Content - JR Auto Resizer** to configure:

| Option | Default | Description |
|--------|---------|-------------|
| **Max Width Intro** | 400px | Maximum width for intro images |
| **Max Width Intro Small** | 200px | Width for small intro images (srcset) |
| **Max Width Full** | 800px | Maximum width for full article images |
| **Max Width Full Small** | 400px | Width for small full images (srcset) |
| **WebP Quality** | 80 | Compression quality (0-100) |
| **Keep Original File** | No | Keep or delete original after optimization |

## 🔧 How It Works

1. When you save an article in Joomla, the plugin hooks into the `onContentBeforeSave` event
2. It checks for Intro Image and Full Article Image fields
3. For each image, it:
   - Resizes to the configured maximum width (if larger)
   - Converts to WebP format
   - Generates a smaller version for responsive images
4. Updates the article's image paths to point to the new optimized files
5. Optionally deletes the original file to save disk space

### Generated Files

For an original image `my-image.jpg`, the plugin creates:
- `my-image_intro.webp` - Optimized intro image
- `my-image_intro_small.webp` - Small intro image for srcset
- `my-image_full.webp` - Optimized full article image
- `my-image_full_small.webp` - Small full image for srcset

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 💖 Support This Project

If you find this plugin useful, consider supporting its development:

[![GitHub Sponsors](https://img.shields.io/badge/Sponsor-GitHub-pink?logo=github)](https://github.com/sponsors/mabdelaziz77)
[![PayPal](https://img.shields.io/badge/Donate-PayPal-blue?logo=paypal)](https://paypal.me/mabdelaziz77)

## 📄 License

This project is licensed under the GNU General Public License v2.0 or later - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Mohamed Abdelaziz** - [JoomReem](https://www.joomreem.com)

- Website: [joomreem.com](https://www.joomreem.com)
- Email: admin@joomreem.com
- GitHub: [@mabdelaziz77](https://github.com/mabdelaziz77)

---

Made with ❤️ for the Joomla community
