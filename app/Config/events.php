<?php

// In app/Config/events.php

// Load event listeners
App::uses('SlideListener', 'Lib/Event');
App::uses('CakeEventManager', 'Event');

// Attach listeners.
CakeEventManager::instance()->attach(new SlideListener());
