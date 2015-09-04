<?php

/**
 * Abstract base controller.
 *
 * Rabota kontrollerov realizovana s pomoshch`iu chankov.
 * Chankami mozhno upravliat`. Ikh mozhno pereopredeliat`.
 * Vy`polnenie dei`stvii` s uchetom prav
 * Mehanizm soobshchenii` o rezul`tatakh dei`stvii`
 *
 * @package General.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
abstract class Zero_Controller
{
    /**
     * Obrabaty`vaemaia model` (ob``ekt)
     *
     * @var Zero_Model
     */
    protected $Model = null;

    /**
     * Predstavlenie
     *
     * @var Zero_View
     */
    protected $View = null;

    /**
     * The compile tpl in string and out
     *
     * @var bool
     */
    protected $ViewTplOutString = false;

    /**
     * Sluzhebny`i` massiv dlia khraneniia i dostupa k razlichnoi` sluzhebnoi` informatcii.
     *
     * Osobenno udobno pri khranenii kontrollera v sessii
     *
     * @var array
     */
    protected $Params = [];

    /**
     * Massiv soobshchenii` sistemy`
     *
     * @var array
     */
    private static $_Message = [];

    /**
     * Получение сообщения
     *
     * @return array ['Code' => int, 'Message' => string, 'ErrorStatus' => bool]
     */
    public function GetMessage()
    {
        if ( count(self::$_Message) == 0 )
            $this->SetMessage();
        return self::$_Message;
    }

    /**
     * Установка сообщения
     *
     * @param int $code код сообщения
     * @param array $params параметры сообщения (sprintf)
     */
    public function SetMessage($code = 0, $params = [])
    {
        $arr = Zero_I18n::Message(get_class($this), $code, $params);
        if ( -1 == $code || 403 == $code || 404 == $code || 5000 <= $code )
            $errorStatus = true;
        else
            $errorStatus = false;
        self::$_Message = [
            'Code' => $arr[0],
            'Message' => $arr[1],
            'ErrorStatus' => $errorStatus,
        ];
    }

    /**
     * Poluchenie massiva soobshchenii` o rezul`tate dei`stvii` pol`zovatelia.
     *
     * S uchetom perevoda
     *
     * @return array soobshcheniia
     * @deprecated GetMessage
     */
    public function Get_Message()
    {
        if ( count(self::$_Message) == 0 )
            $this->Set_Message();
        return self::$_Message;
    }

    /**
     * Dobavlenie soobshchenii` o rezul`tate dei`stvii` pol`zovatelia.
     *
     * @param string $message soobshchenie
     * @param int $code kod soobshcheniia
     * @return int
     * @deprecated SetMessage()
     */
    public function Set_Message($message = '', $code = 0)
    {
        if ( 0 != $code )
            $errorStatus = true;
        else
            $errorStatus = false;
        self::$_Message = [
            'Code' => $code,
            'Message' => Zero_I18n::Controller(get_class($this), $message),
            'ErrorStatus' => $errorStatus,
        ];

        $arr = func_get_args();
        switch ( count($arr) )
        {
            case 3:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2]);
                break;
            case 4:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3]);
                break;
            case 5:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4]);
                break;
            case 6:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4], $arr[5]);
                break;
            case 7:
                self::$_Message['Message'] = sprintf(self::$_Message['Message'], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6]);
                break;
        }
        return $code ? false : true;
    }

    /**
     * Контроллер по умолчанию.
     *
     * @return Zero_View
     */
    public function Action_Default()
    {
        $this->Chunk_Init();
        $this->Chunk_View();
        return $this->View->Fetch($this->ViewTplOutString);
    }

    /**
     * Инициализация контроллера до его выполнения
     *
     * @return bool
     */
    protected function Chunk_Init()
    {
        // Шаблон
        if ( isset($this->Params['view']) )
            $this->View = new Zero_View($this->Params['view']);
        else if ( isset($this->Params['tpl']) )
            $this->View = new Zero_View($this->Params['tpl']);
        else if ( isset($this->Params['template']) )
            $this->View = new Zero_View($this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        // Модель (пример)
        // $this->Model = Zero_Model::Makes('Zero_Users');
        return true;
    }

    /**
     * Вывод данных операции контроллера в шаблон
     *
     * @return bool
     */
    protected function Chunk_View()
    {
        $this->View->Assign('variable', 'value');
        return true;
    }

    /**
     * Фабрика по созданию контроллеров.
     *
     * @param string $class_name имиа контроллера эекземплиар которого создаетсиа
     * @param array $properties входные параметры плагина
     * @return Zero_Controller
     * @throws Exception
     */
    public static function Makes($class_name, $properties = [])
    {
        if ( '' == $class_name )
            throw new Exception('Имя класса создаваемого контроллера не указано', -1);
        if ( false == Zero_App::Autoload($class_name) )
            throw new Exception('Контроллер "' . $class_name . '" отсутсвует в приложении', -1);
        $Controller = new $class_name();
        foreach ($properties as $property => $value)
        {
            $Controller->Params[$property] = $value;
        }
        return $Controller;
    }

    /**
     * Fabrika po sozdaniiu kontrollerov.
     *
     * Rabotaet cherez sessiiu. Indeks: $class_name
     *
     * @param string $class_name imia kontrollera e`ekzempliar kotorogo sozdaetsia
     * @param array $properties vhodny`e parametry` plagina
     * @return Zero_Controller
     */
    public static function Factory($class_name, $properties = [])
    {
        if ( !$result = Zero_Session::Get($class_name) )
        {
            $result = self::Makes($class_name, $properties);
            Zero_Session::Set($class_name, $result);
        }
        return $result;
    }

    /**
     * Save ob``ekta v reestr.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     */
    public function Factory_Set()
    {
        $index = get_class($this);
        Zero_Session::Set($index, $this);
    }

    /**
     * Save ob``ekta v reestr.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     *
     * @return mixed
     */
    public function Factory_Get()
    {
        $index = get_class($this);
        return Zero_Session::Get($index);
    }

    /**
     * Udalenie ob``ekta iz reestra.
     *
     * Indeks source + [_{$id} - esli 0 < $flag]
     */
    public function Factory_Unset()
    {
        $index = get_class($this);
        Zero_Session::Rem($index);
    }
}
