<?php
namespace Sportal\FootballApi\Cache\Lock;

use Predis\Command\ScriptCommand;

class UnlockCommand extends ScriptCommand
{

    public function getKeysCount()
    {
        return 1;
    }

    public function getScript()
    {
        return <<<LUA
if redis.call("get",KEYS[1]) == ARGV[1]
then
    return redis.call("del",KEYS[1])
else
    return 0
end
LUA;
    }
}
