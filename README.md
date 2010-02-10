Configuration:

Add the following to the factories.yml under the loggers section:

    redis_logger:
      class: RedisLogger
        param: 
          host: localhost
          port: 6379
          maxlogs: 1000
          
And in the ProjectConfiguration::setup() method

    $this->enablePlugins('avRedisLoggerPlugin');
    
The logs will be pushed to a list with this key:

    <?php $key = 'logs.'.gethostname(); ?>