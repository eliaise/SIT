FROM php:8.2-fpm

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli

# Create a non-root user and group
RUN useradd -m -u 1000 phpuser

# Change ownership of directories needed by PHP-FPM
RUN chown -R phpuser:phpuser /var/www/html

# Switch to non-root user
USER phpuser

# Expose the port (optional)
EXPOSE 9000

# The default command for PHP-FPM
CMD ["php-fpm"]
