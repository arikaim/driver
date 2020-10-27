<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Core\Driver\Traits;

/**
 * Driver trait
*/
trait Driver
{
    /**
     * Driver name
     *
     * @var string
    */
    protected $driverName = null;

    /**
     * Driver class
     *
     * @var string|null
     */
    protected $driverClass = null;

    /**
     * Driver version
     *
     * @var string
     */
    protected $driverVersion = '1.0.0';

    /**
     * Driver title (display name)
     *
     * @var string
     */
    protected $driverTitle = null;

    /**
     * Driver description
     *
     * @var string
     */
    protected $driverDescription = null;

    /**
     * Driver category
     *
     * @var string
     */
    protected $driverCategory = null;

    /**
     * Driver config
     *
     * @var array
     */
    protected $driverConfig;
    
    /**
     * Driver options
     *
     * @var array
     */
    protected $driverOptions = [];

    /**
     * Driver extension name
     *
     * @var string
     */
    protected $driverExtension;

    /**
     * Driver instance
     *
     * @var object|null
     */
    protected $instance;

    /**
     * Return driver name.
     *
     * @return string
     */
    public function getDriverName()
    {
        return $this->driverName;
    }

    /**
     * Get driver instance
     *
     * @return object
     */
    public function getInstance()
    {
        return $this->instance ?? $this;
    }

    /**
     * Return driver display name.
     *
     * @return string
     */
    public function getDriverTitle()
    {
        return $this->driverTitle ?? $this->getDriverName();
    }

    /**
     * Return driver description.
     *
     * @return string
     */
    public function getDriverDescription()
    {
        return $this->driverDescription;
    }

    /**
     * Return driver category.
     *
     * @return string
     */
    public function getDriverCategory()
    {
        return $this->driverCategory;
    }

    /**
     * Return driver version.
     *
     * @return string
     */
    public function getDriverVersion()
    {
        return $this->driverVersion;
    }

    /**
     * Return driver extension name (if driver class is located in extension)
     *
     * @return string
    */
    public function getDriverExtensionName()
    {
        return $this->driverExtension;
    }

    /**
     * Get driver class
     *
     * @return string
     */
    public function getDriverClass()
    {
        return $this->driverClass ?? \get_class();
    }

    /**
     * Set driver class
     *
     * @param string $class
     * @return void
     */
    public function setDriverClass($class)
    {
        $this->driverClass = $class;
    }

    /**
     * Get driver config
     *
     * @return array
     */
    public function getDriverConfig()
    {
        return (\is_array($this->driverConfig) == true) ? $this->driverConfig : [];
    }

    /**
     * Set driver name, title, category, description , version params
     *
     * @param string $name
     * @param string|null $category
     * @param string|null $title
     * @param string|null $class
     * @param string|null $description
     * @param string|null $version
     * @param string|null $extension
     * @return void
    */
    public function setDriverParams($name, 
        $category = null,
        $title = null,
        $description = null,
        $version = null,
        $extension = null,
        $class = null)
    {
        $this->driverName = $name;
        $this->driverCategory = $category;
        $this->driverTitle = $title;
        $this->driverClass = $class;
        $this->driverDescription = $description;
        $this->driverVersion = $version ?? '1.0.0';
        $this->driverExtension = $extension;
    }

    /**
     * Initialize driver
     *
     * @return void
     */
    public function initDriver($properties)
    {     
        $config = $properties->getValues();
        $this->instance = new $this->driverClass($config);   
    }

    /**
     * Build driver config properties
     *
     * @param Arikaim\Core\Collection\Properties $properties;
     * 
     * @return array
     */
    public function createDriverConfig($properties)
    {
    }

    /**
     * Get driver option
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getDriverOption($name, $default = null)
    {
        return $this->driverOptions[$name] ?? $default;
    }

    /**
     * Set driver option
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setDriverOption($name, $value)
    {
        $this->driverOptions[$name] = $value;
    }

    /**
     * Set driver options
     *
     * @param array $options
     * @return void
     */
    public function setDriverOptions(array $options)
    {
        $this->driverOptions = $options;
    } 

    /**
     * Get driver options
     *    
     * @return array
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    } 
}
