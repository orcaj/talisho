![](https://codebuild.us-east-1.amazonaws.com/badges?uuid=eyJlbmNyeXB0ZWREYXRhIjoiMHVkNUlNbjFoWWdwa3lxa3hVbWtiVkQvNEdjVit0dG5STnYxQmJPWE5pZk5Kdk40V2txVkhiM3M3d09GMWwzZDdmZHRBWVMzbGVQVk5wWUo2Z21NbmFNPSIsIml2UGFyYW1ldGVyU3BlYyI6ImhxSkVmSjJaSUFhMVByQnciLCJtYXRlcmlhbFNldFNlcmlhbCI6MX0%3D&branch=develop)

### Instructions for Setting Up Tests to Run Locally with Laradock
1. Create a 'testing' database alongside default database (need to leverage MySQL as opposed to sqlite due to sqlite
 limitations around setting non-nullable relationship FKs without default values) 
2. Make a copy of .env saved as .env.testing in project root.
3. Set .env.testing APP_ENV=testing
4. Set DB_DATABASE=testing 
5. Set DB_USERNAME=root
6. Set DB_PASSWORD=root


### Setup QR Codes
1. Seed Public QR Code data - `php artisan db:seed --class=PublicQrSeeder`
2. Seed Internal QR Code data - `php artisan qr:internal:seed`
3. Generate Public QR codes - `php artisan qr:public:generate`
4. Project QR codes will be generated on project creation. Alternatively, they can be generated on demand by firing an `event(new App\Events\ProjectCreated($project))` where $project is the project of interest.

Note: URL locally will be localhost.com/qr-codes/{slug}. If testing on phone it will obviously not be found. Can just make sure the QR points to the correct URL and test that the redirect works correctly in the browser.

### Dependencies
- npm (v12):
- php (v7.3)
- composer (v1.9.3)


### Laravel Nova
```bash
composer config http-basic.nova.laravel.com <<NOVA_USERNAME>> <<NOVA_PASSWORD>>
```

Replace `<<NOVA_USERNAME>>` and `<<NOVA_PASSWORD>>` with actual credentials.