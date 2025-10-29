# Livecoat Starter Kit

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-12+-FF2D20.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3+-FB70A5.svg)](https://laravel-livewire.com)
[![BasecoatUI](https://img.shields.io/badge/BasecoatUI-Modern-6366F1.svg)](https://basecoatui.com)

⚡ **A powerful Laravel starter kit combining Livewire with BasecoatUI.com for rapid application development**

Livecoat is a comprehensive starter template designed for backend developers who want to kickstart their Laravel applications with modern UI components and essential features built-in. No more wasting time on repetitive setup tasks - focus on building your unique features from day one.

## 🚀 Features

### 🔐 Authentication & Security
- **TOTP Authenticator** - Built-in two-factor authentication using pragmRX
- **Google reCAPTCHA v2** - Protect your forms from bots
- **Role-based Permissions** - Integrated Spatie role and permissions system
- **Secure session management** - Laravel's robust authentication system

### 🎨 User Interface & Experience
- **BasecoatUI Components** - Beautiful, modern UI components (shadcn-inspired)
- **Dark/Light Mode** - Built-in theme switching with system preference detection
- **Responsive Design** - Mobile-first approach with Tailwind CSS
- **Filament 4 Support** - Component-based integration

### ⚙️ Developer Experience
- **Customizable App Settings** - Easily change app name, version, icon, and logo
- **Livewire 3+** - Reactive UI components without writing JavaScript
- **Laravel 12+** - Latest Laravel features and best practices
- **Component Architecture** - Reusable, maintainable code structure

### 🛠️ Built-in Tools
- **User Management** - Complete user CRUD operations
- **Role Management** - Flexible role and permission system
- **Profile Management** - User profile editing with avatar support
- **Settings Panel** - Application configuration interface

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or PostgreSQL
- Redis (recommended for caching)

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/developerabdan/livecoat.git
   cd livecoat
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your database**
   ```bash
   # Edit .env file with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seed**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   npm run dev
   ```

7. **Visit your application**
   ```
   http://localhost:8000
   ```

## ⚙️ Configuration

### Customizing App Settings

You can easily customize your application through the admin panel or by editing the configuration files:

- **App Name,Logo, Other Settings**: Visit `/app/settings/system-setting`
- **Theme Colors**: Modify `resources/css/app.css`

### Role & Permissions

The starter kit comes with a pre-configured role system:

- **Super Admin**: Full system access

Add custom roles and permissions through the admin panel or programmatically:

```php
// Create a new role
$role = Role::create(['name' => 'moderator']);

// Assign permissions
$role->givePermissionTo(['edit-posts', 'delete-comments']);
```

## 🎯 Usage Examples

### Creating a New Livewire Component

```bash
php artisan make:livewire UserProfile
```

This creates:
- `app/Livewire/UserProfile.php`
- `resources/views/livewire/user-profile.blade.php`

### Using BasecoatUI Components

```blade
<x-basecoat::button variant="primary" size="lg">
    Click me
</x-basecoat::button>

<x-basecoat::card>
    <x-basecoat::card-header>
        <h3>Card Title</h3>
    </x-basecoat::card-header>
    <x-basecoat::card-content>
        <p>Card content goes here</p>
    </x-basecoat::card-content>
</x-basecoat::card>
```

### Adding Permissions to Routes

```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
```

## 📁 Project Structure

```
├── app/
│   ├── Livewire/          # Livewire components
│   ├── Models/            # Eloquent models
│   ├── Http/Controllers/  # HTTP controllers
│   └── Helpers/           # Helper functions
├── resources/
│   ├── views/
│   │   ├── livewire/      # Livewire component views
│   │   └── components/    # Blade components
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/          # Database seeders
│   └── factories/        # Model factories
└── config/               # Configuration files
```

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit your changes** (`git commit -m 'Add amazing feature'`)
4. **Push to the branch** (`git push origin feature/amazing-feature`)
5. **Open a Pull Request**

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Be respectful and constructive

## 📝 Roadmap

- [ ] API authentication with Sanctum
- [ ] Multi-language support
- [ ] Advanced audit logging
- [ ] Email template system
- [ ] File manager component
- [ ] Advanced user analytics
- [ ] WebSocket integration

## 🙏 Acknowledgments

- **[Laravel](https://laravel.com)** - The PHP framework for web artisans
- **[Livewire](https://laravel-livewire.com)** - Dynamic interfaces without JavaScript
- **[BasecoatUI](https://basecoatui.com)** - Beautiful UI components
- **[Spatie](https://spatie.be)** - Excellent Laravel packages
- **[pragmRX](https://github.com/Pragmarx)** - TOTP authentication library
- **[Filament](https://filamentphp.com)** - Elegant admin panels

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## ⭐ Show Your Support

If you find this starter kit helpful, please consider:

- **⭐ Starring this repository** - It helps more developers discover it
- **🐛 Reporting issues** - Help us improve by reporting bugs
- **💡 Suggesting features** - Share your ideas for improvements
- **📢 Sharing with others** - Spread the word about Livecoat

---

**Made with ❤️ for the Laravel community**

Built by backend developers, for backend developers. Stop wasting time on boilerplate and start building amazing applications today!