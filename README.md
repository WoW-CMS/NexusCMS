# NexusCMS

<p align="center">
<a href="https://github.com/yourusername/NexusCMS/actions"><img src="https://github.com/yourusername/NexusCMS/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/yourusername/nexuscms"><img src="https://img.shields.io/packagist/v/yourusername/nexuscms" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/yourusername/nexuscms"><img src="https://img.shields.io/packagist/l/yourusername/nexuscms" alt="License"></a>
</p>

## About NexusCMS

Born from our earlier BlizzCMS, a PHP/CodeIgniter 3 project, NexusCMS is a Laravel-based platform engineered for teams that demand reliability, flexibility, and developer-friendly architecture. We have distilled years of client feedback, security audits, and performance tuning into a single codebase that grows with your business, not against it.

## Key Highlights

- **Years of Expertise** – Over a decade of focused development on CMS architecture and implementation.  
- **Laravel 12.x Core** – Modern PHP foundation with strict PSR standards and battle-tested packages.  
- **Scalable Infrastructure** – Multi-database, Redis cache, queue workers, and horizontal scaling support.  

## Project Maturity

NexusCMS is currently in **public alpha** and is **not recommended for production use**. While core functionality is stable and has been tested in several mid-scale environments, the software is still under active development. We are expanding modules, refining documentation, and welcoming community contributions.

## Quick Start

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/NexusCMS.git
   cd NexusCMS
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```

3. Configure your environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations (configure your database in `.env` first):
   ```bash
   php artisan migrate
   ```

5. Start the local server:
   ```bash
   php artisan serve
   ```

Visit [http://localhost:8000](http://localhost:8000) and log in with the default admin credentials provided in the `.env` file.

For detailed setup instructions, Docker deployment, and advanced configuration, see the [full documentation]().
