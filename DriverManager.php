<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Core\Driver;

use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Collection\Properties;
use Arikaim\Core\Collection\PropertiesFactory;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Interfaces\Driver\DriverRegistryInterface;
use Arikaim\Core\Interfaces\Driver\DriverManagerInterface;

/**
 * Driver manager
*/
class DriverManager implements DriverManagerInterface
{
    /**
     * Driver registry adapter
     *
     * @var DriverRegistryInterface
     */
    protected $driverRegistry;

    /**
     * Constructor
     * 
     * @param DriverRegistryInterface $driverRegistry
     */
    public function __construct(DriverRegistryInterface $driverRegistry)
    {
        $this->driverRegistry = $driverRegistry;
    }

    /**
     * Create driver
     *
     * @param string $name Driver name 
     * @param array $options  
     * @param array|null $config Drievr config properties
     * @return DriverInterface|false
     */
    public function create(string $name, array $options = [], ?array $config = null)
    {       
        $driverInfo = $this->driverRegistry->getDriver($name);
        if ($driverInfo === false) {          
            return false;
        }
      
        $config = $config ?? $driverInfo['config'];

        $properties = PropertiesFactory::createFromArray($config); 
        $driver = Factory::createInstance($driverInfo['class']); 

        if ($driver instanceof DriverInterface) {
            $driver->setDriverOptions($options);  
            $driver->setDriverConfig($properties->getValues());             
            $driver->initDriver($properties);           
        } 

        return $driver;
    }

    /**
      * Install driver
      *
      * @param string|object $name Driver name
      * @param string|null $class full class name or driver object ref
      * @param string|null $category
      * @param string|null $title
      * @param string|null $description
      * @param string|null $version
      * @param array $config
      * @param string|null $extension
      * @return boolean
    */
    public function install(
        $name, 
        ?string $class = null,
        ?string $category = null,
        ?string $title = null,
        ?string $description = null,
        ?string $version = null,
        array $config = [],
        ?string $extension = null): bool
    {      
        $info = $this->getDriverParams($name);

        if (\is_array($info) == false) {
            $version = $version ?? '1.0.0';
            $info = [
                'name'           => $name,
                'category'       => $category,
                'title'          => $title,
                'class'          => $class,
                'description'    => $description,
                'version'        => $version,
                'extension_name' => $extension,
                'config'         => $config
            ];
        }

        return $this->driverRegistry->addDriver($info['name'],$info);
    }

    /**
     * Get driver params
     *
     * @param string|object $driver Driver obj ref or driver class
     * @return array|false
     */
    protected function getDriverParams($driver)
    {
        $driver = (\is_string($driver) == true && \class_exists($driver) == true) ? Factory::createInstance($driver) : $driver;   
        
        if (\is_object($driver) == false) {
            return false;
        }

        $properties = new Properties([],false);   
        $callback = function() use($driver,$properties) {
            $driver->createDriverConfig($properties);           
            return $properties;
        };
      
        $config = $callback()->toArray();     
        
        return [
            'name'        => $driver->getDriverName(),
            'category'    => $driver->getDriverCategory(),
            'title'       => $driver->getDriverTitle(),
            'class'       => $driver->getDriverClass(),
            'description' => $driver->getDriverDescription(),
            'version'     => $driver->getDriverVersion(),
            'config'      => $config
        ];        
    }

    /**
     * Uninstall driver
     *
     * @param string $name Driver name   
     * @return boolean
     */
    public function unInstall(string $name): bool
    {
        return $this->driverRegistry->removeDriver($name);       
    }
    
    /**
     * Return true if driver exsits
     *
     * @param string $name Driver name
     * @return boolean
     */
    public function has(string $name): bool
    {
        return $this->driverRegistry->hasDriver($name);
    }

    /**
     * Get driver
     *
     * @param string $name Driver name
     * @return object|false
     */
    public function getDriver(string $name)
    {
        return $this->driverRegistry->getDriver($name);
    }

    /**
     * Save driver config
     *
     * @param string $name Driver name
     * @param array|object $config
     * @return boolean
     */
    public function saveConfig(string $name, $config): bool
    {            
        $config = (\is_object($config) == true) ? $config->toArray() : $config;

        return $this->driverRegistry->saveConfig($name,$config);
    }

    /**
     * Get driver config
     *
     * @param string $name Driver name
     * @return Properties
     */
    public function getConfig(string $name)
    {
        $config = $this->driverRegistry->getDriverConfig($name);
        
        return PropertiesFactory::createFromArray($config);         
    }

    /**
     * Get drivers list
     *
     * @param string|null   $category
     * @param integer|null  $status
     * @return array
     */
    public function getList(?string $category = null, ?int $status = null): array
    {
        return $this->driverRegistry->getDriversList($category,$status);
    }

    /**
     * Enable driver
     *
     * @param string $name
     * @return boolean
     */
    public function enable(string $name): bool
    {
        return $this->driverRegistry->setDriverStatus($name,1);
    }

    /**
     * Disable driver
     *
     * @param string $name
     * @return boolean
     */
    public function disable(string $name): bool
    {
        return $this->driverRegistry->setDriverStatus($name,0);
    }
}
