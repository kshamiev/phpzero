<?php

/**
 * Abstract base controller.
 *
 * Rabota kontrollerov realizovana s pomoshch`iu chankov.
 * Chankami mozhno upravliat`. Ikh mozhno pereopredeliat`.
 * Vy`polnenie dei`stvii` s uchetom prav
 * Mehanizm soobshchenii` o rezul`tatakh dei`stvii`
 *
 * @package Zero.Component
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
        if (count(self::$_Message) == 0)
            $this->SetMessage();
        return self::$_Message;
    }

    /**
     * Установка сообщения об успехе
     *
     * @param int $code код сообщения
     * @param array $params параметры сообщения (sprintf)
     * @return bool флаг ошибки
     */
    public function SetMessage($code = 0, $params = [])
    {
        self::$_Message = [
            'Code' => $code,
            'Message' => Zero_I18n::Message(get_class($this), $code, $params),
            'ErrorStatus' => false,
        ];
        return true;
    }

    /**
     * Установка сообщения об ошибке
     *
     * @param int $code код сообщения
     * @param array $params параметры сообщения (sprintf)
     * @return bool флаг ошибки
     */
    public function SetMessageError($code = 0, $params = [])
    {
        self::$_Message = [
            'Code' => $code,
            'Message' => Zero_I18n::Message(get_class($this), $code, $params),
            'ErrorStatus' => true,
        ];
        return false;
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
        return $this->View;
    }

    /**
     * Инициализация контроллера
     *
     * Может быть переопределен конкретным контроллером
     *
     * @return bool
     */
    protected function Chunk_Init()
    {
        // Шаблон
        if (isset($this->Params['view']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['view']);
        else if (isset($this->Params['tpl']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['tpl']);
        else if (isset($this->Params['template']))
            $this->View = new Zero_View(get_class($this) . '_' . $this->Params['template']);
        else
            $this->View = new Zero_View(get_class($this));
        // Модель (пример)
        // $this->Model = Zero_Model::Makes('Zero_Users');
        // $this->Model = Zero_Users::Make();
        return true;
    }

    /**
     * Вывод данных операции контроллера в шаблон
     *
     * Может быть переопределен конкретным контроллером
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
        if ('' == $class_name)
            throw new Exception('Имя класса создаваемого контроллера не указано', -1);
        if (false == Zero_App::Autoload($class_name))
            throw new Exception('Контроллер "' . $class_name . '" отсутсвует в приложении', -1);
        $Controller = new $class_name();
        foreach ($properties as $property => $value) {
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
        if (!$result = Zero_Session::Get($class_name)) {
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
