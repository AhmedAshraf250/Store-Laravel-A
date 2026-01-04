<?php
class MyListener
{
    public $tries = 3;
    public $priority = 'high';
}

$listener = new MyListener();

// 1. التجميد (Serialization)
$frozen = serialize($listener);
echo "الشكل المخزن في قاعدة البيانات:\n" . $frozen . "\n\n";

// 2. الإحياء (Deserialization)
$revived = unserialize($frozen);
echo "قيمة الـ tries بعد الإحياء: " . $revived->tries;


// لنفترض أننا لا نعرف ما هو كلاس الـ $revived
$inspector = new ReflectionClass($revived);

// سؤال الكلاس: هل عندك خاصية اسمها tries؟
if ($inspector->hasProperty('tries')) {
    $property = $inspector->getProperty('tries');
    echo "المحقق وجد خاصية tries وقيمتها هي: " . $property->getValue($revived);
}

















/*********************************************************************************** 
 * SECTION 1: THE APPLICATION CLASSES (Defining Dependencies)
 * These are the classes you write in your app. Notice the nested hierarchy.
 * NotificationService -> SmsGateway -> ConfigReader
 ***********************************************************************************/

class ConfigReader
{
    public function get($key)
    {
        return "Setting Value";
    }
}

class SmsGateway
{
    protected $config;
    // SmsGateway depends on ConfigReader
    public function __construct(ConfigReader $config)
    {
        $this->config = $config;
    }
}

class NotificationService
{
    protected $sms;
    // NotificationService depends on SmsGateway
    public function __construct(SmsGateway $sms)
    {
        $this->sms = $sms;
    }

    // Method Injection Example
    public function sendFinal(Logger $logger)
    {
        // Logic here...
    }
}

/*********************************************************************************** 
 * SECTION 2: THE RECURSIVE RESOLVER (Deep Dependency Resolution)
 * This logic mimics how Laravel's Container dives deep to resolve nested classes.
 ***********************************************************************************/

class Container
{
    public function make($className)
    {
        $reflector = new ReflectionClass($className);

        // Check if we can actually create this class
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        // BASE CASE: If no constructor exists, just instantiate (End of recursion)
        if (is_null($constructor)) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyType = $parameter->getType()->getName();

            // RECURSIVE CALL: Dive deeper into the dependency tree
            $dependencies[] = $this->make($dependencyType);
        }

        // Return the object with all nested dependencies injected
        return $reflector->newInstanceArgs($dependencies);
    }
}

/*********************************************************************************** 
 * SECTION 3: METHOD INJECTION ENGINE (Resolving Parameters at Runtime)
 * This part handles injecting dependencies into specific methods, like Listeners or Controllers.
 ***********************************************************************************/

function resolveMethodInjection($instance, $methodName)
{
    $reflectionMethod = new ReflectionMethod($instance, $methodName);
    $resolvedParams = [];

    foreach ($reflectionMethod->getParameters() as $param) {
        if ($param->hasType() && !$param->getType()->isBuiltin()) {
            $type = $param->getType()->getName();

            // Resolve the class using our Container
            $container = new Container();
            $resolvedParams[] = $container->make($type);
        }
    }

    // Execute the method with the resolved objects
    return $reflectionMethod->invokeArgs($instance, $resolvedParams);
}

/*********************************************************************************** 
 * * FINAL EXECUTION: Putting it all together
 ***********************************************************************************/

$app = new Container();
$service = $app->make('NotificationService'); 

// The $service is now a fully built object with SmsGateway and ConfigReader inside it!