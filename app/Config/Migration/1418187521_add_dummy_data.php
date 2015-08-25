<?php

class AddDummyData extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_dummy_data';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
        ),
        'down' => array(
        ),
    );

    /**
     * Before migration callback.
     *
     * @param string $direction Direction of migration process (up or down)
     *
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback.
     *
     * @param string $direction Direction of migration process (up or down)
     *
     * @return bool Should process continue
     */
    public function after($direction)
    {
        // User data
        $User = ClassRegistry::init('User');
        if ($direction === 'up') {
            // add admin account to user table
            $data = array();
            $data['User']['username'] = 'admin@example.com';
            $data['User']['password'] = 'passw0rd';
            $data['User']['display_name'] = 'Admin';

            $User->create();
            if ($User->save($data)) {
                $this->callback->out('User data was created...');
            } else {
                echo 'User data creation failed......';
            }
        }

        return true;
    }
}
