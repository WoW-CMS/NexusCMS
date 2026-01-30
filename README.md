# NexusCMS

<p align="center">
![Downloads](https://img.shields.io/github/downloads/wow-cms/nexuscms/total)
![License](https://img.shields.io/github/license/wow-cms/nexuscms)
![Release](https://img.shields.io/github/v/release/wow-cms/nexuscms)
</p>

## About NexusCMS

Born from our earlier BlizzCMS, a PHP/CodeIgniter 3 project, NexusCMS is a Laravel-based platform designed for teams seeking reliability, flexibility, and a developer-friendly workflow. Over time, we’ve gathered years of community feedback, feature requests, and performance improvements, shaping a single, cohesive codebase that grows with your project, rather than getting in the way.

Our focus has always been on making development easier and more approachable. While we are a team with technical training and experience, we are not “gurus”—we build tools that make sense for real people working on real projects. This philosophy has guided every decision in NexusCMS, from its architecture to its user interface.

In addition to supporting modern features, we’ve ensured compatibility with all World of Warcraft expansions, including Classic, Retail, and Wrath of the Lich King, as well as the legacy versions of the game. Whether you’re managing a private server, building a community project, or experimenting with new ideas, NexusCMS provides a flexible, stable foundation to help you succeed.

With a strong focus on community-driven development, we continue to refine and expand NexusCMS based on the feedback and needs of the people who use it, making it not just a tool, but a platform built with the community, for the community.

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
