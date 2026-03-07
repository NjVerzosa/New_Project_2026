<?php
// config/env.loader.php

class EnvironmentLoader {
    private static $loaded = false;
    private static $variables = [];
    
    /**
     * Load environment variables from .env file
     * 
     * @param string $path Path to .env file
     * @return void
     * @throws Exception If .env file not found
     */
    public static function load($path = null) {
        if (self::$loaded) {
            return;
        }
        
        if ($path === null) {
            $path = __DIR__ . '.env';
        }
        
        if (!file_exists($path)) {
            throw new Exception('.env file not found at: ' . $path);
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse line
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Remove quotes if present
                $value = trim($value, '"\'');
                
                // Handle boolean values
                if (strtolower($value) === 'true') {
                    $value = true;
                } elseif (strtolower($value) === 'false') {
                    $value = false;
                } elseif (strtolower($value) === 'null') {
                    $value = null;
                }
                
                // Set environment variable
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                self::$variables[$name] = $value;
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable
     * 
     * @param string $key Variable name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($key, $default = null) {
        // Check if variable exists in environment
        $value = getenv($key);
        
        if ($value !== false) {
            return self::castValue($value);
        }
        
        // Check if we have it in our loaded variables
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }
        
        return $default;
    }
    
    /**
     * Check if application is in development mode
     * 
     * @return bool
     */
    public static function isDevelopment() {
        return self::get('APP_ENV') === 'development';
    }
    
    /**
     * Check if application is in production mode
     * 
     * @return bool
     */
    public static function isProduction() {
        return self::get('APP_ENV') === 'production';
    }
    
    /**
     * Check if debugging is enabled
     * 
     * @return bool
     */
    public static function isDebug() {
        return self::get('APP_DEBUG') === true || self::get('APP_DEBUG') === 'true';
    }
    
    /**
     * Cast value to appropriate type
     * 
     * @param mixed $value
     * @return mixed
     */
    private static function castValue($value) {
        if (is_string($value)) {
            if (strtolower($value) === 'true') {
                return true;
            }
            if (strtolower($value) === 'false') {
                return false;
            }
            if (strtolower($value) === 'null') {
                return null;
            }
            if (is_numeric($value)) {
                return strpos($value, '.') !== false ? (float)$value : (int)$value;
            }
        }
        
        return $value;
    }
}

// Auto-load environment variables
try {
    EnvironmentLoader::load();
} catch (Exception $e) {
    // Log error but don't expose in production
    error_log('Failed to load .env file: ' . $e->getMessage());
    
    // Only show error in development
    if (EnvironmentLoader::isDevelopment()) {
        die('Error loading environment: ' . $e->getMessage());
    }
}