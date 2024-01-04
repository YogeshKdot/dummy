# Use the official PHP 7.4 image as the base image
FROM php:7.4

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the PHP application files to the container
COPY . /var/www/html

# Install any additional dependencies or extensions you may need
# For example, if you need the MySQL extension:
# RUN docker-php-ext-install mysqli pdo_mysql

# Expose the port your application will run on
EXPOSE 80

# Command to run when the container starts
CMD ["php", "-S", "0.0.0.0:80"]
