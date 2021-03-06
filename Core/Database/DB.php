<?php 
/**
 * @Created 18.05.2020 22:40:39
 * @Project simpleFramework
 * @Author Mehmet Emre Tülek <memretulek@gmail.com>
 * @Class DB
 * @package Core\Database
 */


namespace Core\Database;


use Core\Config\Config;
use Core\Exceptions\Exceptions;
use Exception;
use PDO;
use PDOStatement;

/**
 * @see Database::selectDB()
 * @method static Database selectDB(string $database)
 * --------------------------------------------------------------------------------------------
 * @see Database::bindQuery()
 * @method static Database bindQuery($query, array $bindings = null, array $options = [])
 * --------------------------------------------------------------------------------------------
 * @see Database::get()
 * @method static array|bool get(string $query, array $bindings = null, $fetchStyle = PDO::FETCH_OBJ)
 * --------------------------------------------------------------------------------------------
 * @see Database::getRow()
 * @method static bool|mixed getRow(string $query, array $bindings = null, $fetchStyle = PDO::FETCH_OBJ)
 * --------------------------------------------------------------------------------------------
 * @see Database::getCol()
 * @method static array|bool getCol(string $query, array $bindings = null, $fetchStyle = PDO::FETCH_COLUMN)
 * --------------------------------------------------------------------------------------------
 * @see Database::getVar()
 * @method static bool|mixed getVar(string $query, array $bindings = null)
 * --------------------------------------------------------------------------------------------
 * @see Database::insert()
 * @method static bool|string insert(string $query, array $bindings = null)
 * --------------------------------------------------------------------------------------------
 * @see Database::update()
 * @method static bool|int update(string $query, array $bindings = null)
 * --------------------------------------------------------------------------------------------
 * @see Database::delete()
 * @method static bool|int delete(string $query, array $bindings = null)
 * --------------------------------------------------------------------------------------------
 * @see Database::transaction()
 * @method static void transaction()
 * --------------------------------------------------------------------------------------------
 * @see Database::close()
 * @method static void close()
 * --------------------------------------------------------------------------------------------
 * @see Database::commit()
 * @method static void commit()
 * --------------------------------------------------------------------------------------------
 * @see Database::rollBack()
 * @method static void rollBack()
 * --------------------------------------------------------------------------------------------
 * @see Database::pdo()
 * @method static PDO pdo()
 * --------------------------------------------------------------------------------------------
 * @see Database::stm()
 * @method static PDOStatement stm()
 *--------------------------------------------------------------------------------------------
 */
class DB {

    /**
     * @var Database
     */
    private static $instance;

    /**
     * @param $name
     * @param $arguments
     * @return Database
     */
    public static function __callStatic($name, $arguments)
    {
        try {

            if(self::$instance === null){
                self::$instance = self::selectDriver(Config::get('app.sql_driver'));
            }

            return call_user_func_array([self::$instance, $name], $arguments);

        }catch (Exception $e){
            Exceptions::debug($e, 2);
        }

        return null;
    }


    /**
     * config/database ayar dosyasında belirtilen driver ile veritabanı bağlantısı oluşturur.
     *
     * @param $driverName
     * @return Database|null
     */
    public static function selectDriver($driverName)
    {
        try {
            return self::$instance = new Database(
                Config::get('database.' . $driverName . '.driver'),
                Config::get('database.' . $driverName . '.host'),
                Config::get('database.' . $driverName . '.database'),
                Config::get('database.' . $driverName . '.user'),
                Config::get('database.' . $driverName . '.password'),
                Config::get('database.' . $driverName . '.charset'),
                Config::get('database.' . $driverName . '.collaction')
            );
        }catch (Exception $e){
            Exceptions::debug($e, 1);
        }

        return null;
    }
}
