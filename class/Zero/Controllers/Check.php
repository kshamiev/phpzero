<?php

/**
 * Controller. Management of access rights.
 *
 * @package Zero.Admin
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Controllers_Check extends Zero_Controller
{
    /**
     * Модель
     *
     * @var Zero_Controllers
     */
    protected $Model = null;

    /**
     * Vy`polnenie dei`stvii`
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View;
    }

    /**
     * Create views.
     *
     * @return boolean статус выполнения чанка
     * @throws Exception
     */
    protected function Chunk_View()
    {
        // отсутсвующие контроллеры
        $controllerNotExist = [];
        $sql = "SELECT Controller FROM Controllers ORDER BY Controller ASC";
        foreach(Zero_DB::Select_Array($sql) as $row)
        {
            if ( false == Zero_App::Autoload($row['Controller'], false) )
                $controllerNotExist[] = $row['Controller'];
        }
        $this->View->Assign('controllerNotExist', $controllerNotExist);
        // дублирующиеся контроллеры
        $sql = "SELECT Controller, COUNT(*) AS Cnt FROM Controllers GROUP BY 1 HAVING COUNT(*) > 1 ORDER BY 1 ASC";
        $this->View->Assign('controllerCopy', Zero_DB::Select_List_Index($sql));
        return true;
    }
}