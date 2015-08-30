<?php
class AddAdminFlagToUsers extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'add_admin_flag_to_users';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'users' => array(
                    'admin' => array(
                        'type' => 'boolean',
                        'null' => false,
                        'default' => false,
                        'after' => 'password',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'users' => array(
                    'admin',
                ),
            ),
        ),
    );

    /**
     * Before migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback
     *
     * @param string $direction Direction of migration process (up or down)
     * @return bool Should process continue
     */
    public function after($direction)
    {
        $User = ClassRegistry::init('User');
        $dataSource = $User->getDataSource();
        if ($direction === 'up') {
            $dataSource->query("UPDATE users set admin = true where id = 1");
        }
        return true;
    }
}
