# NexusCMS

<p align="center">
  <a href="https://packagist.org/packages/WoW-CMS/nexuscms">
    <img src="https://img.shields.io/packagist/v/WoW-CMS/nexuscms" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/WoW-CMS/nexuscms">
    <img src="https://img.shields.io/packagist/l/WoW-CMS/nexuscms" alt="License">
  </a>
  <a href="https://deepwiki.com/WoW-CMS/NexusCMS">
    <img src="https://deepwiki.com/badge.svg" alt="Ask DeepWiki">
  </a>
</p>

## About NexusCMS

**NexusCMS** is a modern Content Management System built with **Laravel 12** and fully compatible with **PHP 8.4**. It is designed to provide a flexible, scalable, and user-friendly platform for managing digital content. Whether you're building a simple blog or a complex website, NexusCMS offers the tools to help you create and manage your content effortlessly.

Currently under active development, NexusCMS aims to offer a powerful solution with a focus on performance, ease of use, and customization.

### Key Features

- **Built with Laravel 12**: Leverages the latest version of Laravel for a modern, robust framework.
- **PHP 8.4 Support**: Enjoy the latest performance improvements and features of PHP 8.4.
- **Modular Design**: Easily extendable through a modular architecture.
- **User-friendly Admin Panel**: A clean, intuitive interface for content management.
- **Multi-language Support**: Easily create content in multiple languages.
- **SEO Friendly**: Tools for optimizing content for search engines.
- **Role-based Access Control**: Manage user permissions with ease.

## Project Status

🚧 **Currently Under Development** 🚧

NexusCMS is in its early stages, and we are continuously adding new features and improving stability. Features and documentation will be updated regularly. Feel free to contribute and help us shape the future of NexusCMS!

## Installation

To get started with NexusCMS, follow the steps below to install it on your server:

### Prerequisites

- PHP 8.4 or higher
- Laravel 12
- Composer
- A web server (Apache, Nginx, etc.)
- A database (MySQL, PostgreSQL, SQLite, etc.)

### Step 1: Clone the Repository

Clone the repository to your local machine or server:

```bash
git clone https://github.com/WoW-CMS/nexuscms.git
cd nexuscms
```

### Step 2: Install Dependencies

Install the project dependencies using Composer:

```bash
composer install
```

### Step 3: Set Up Environment

Copy the .env.example file to .env:

```bash
cp .env.example .env
```

### Step 4: Generate Application Key

Generate the Laravel application key:

```bash
php artisan key:generate
```

### Step 5: Run the Installer

After setting up your environment, navigate to the following URL in your web browser to complete the installation process:

http://localhost/install

The installer will guide you through the final setup steps, including setting up the database and application configurations. Once completed, you'll have a fully functional NexusCMS instance ready to use!


## Contributing

We welcome contributions to NexusCMS! If you'd like to contribute, please follow these steps:

1. **Fork the repository**: Create your own copy of the NexusCMS repository.
2. **Create a new branch**: Create a new branch for your feature or bug fix. Make sure to name it appropriately (e.g., `feature/your-feature` or `bugfix/issue-id`).
3. **Write tests**: If your changes affect functionality, please write tests to ensure your modifications work as expected.
4. **Submit a pull request**: Once your changes are ready, submit a pull request with a detailed description of what you've done and why.

We encourage you to check out our [CONTRIBUTING.md](CONTRIBUTING.md) for more detailed guidelines on contributing, including coding standards, commit message guidelines, and more.

## License

NexusCMS is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for more details.

## Contact

For support or inquiries, feel free to:

- Open an issue on [GitHub](https://github.com/WoW-CMS/nexuscms/issues) if you encounter any problems or have questions.
- Reach out to us via [DeepWiki](https://deepwiki.com/WoW-CMS/NexusCMS) for discussions, advice, or any other inquiries.

We’re happy to help and value your feedback!

