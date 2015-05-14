<?php
/**
 * Инженеринг моделей и контроллеров CRUD по БД (первой по умолчанию)
 *
 * @package Console.Zero.System
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.05.14
 */
class Zero_System_Console_Engine extends Zero_Controller
{
    /**
     * Инженеринг моделей и контроллеров CRUD по БД (первой по умолчанию)
     */
    public function Action_Default()
    {
        $Controller_Factory = new Zero_Engine;
        reset(Zero_App::$Config->Db);
        $nameConnect = key(Zero_App::$Config->Db);
        $Controller_Factory->Factory_Modules_DB($nameConnect);
    }
}
