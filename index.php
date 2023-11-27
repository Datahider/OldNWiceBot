<?php

use losthost\OldNWise\OldNWise;

use losthost\OldNWise\handlers\MessageNotHandledHandle;
use losthost\OldNWise\handlers\CommandDictumHandler;

use losthost\OldNWise\handlers\CallbackMoreHandler;
use losthost\OldNWise\handlers\CallbackAuthorHandler;

use losthost\OldNWise\model\dictum;
use losthost\OldNWise\model\dictum_author;
use losthost\OldNWise\model\user_data;
use losthost\OldNWise\model\server;
use losthost\OldNWise\model\user_server;

require_once 'vendor/autoload.php';

OldNWise::setup();

user_data::initDataStructure();
dictum::initDataStructure();
dictum_author::initDataStructure();
server::initDataStructure();
user_server::initDataStructure();

OldNWise::addHandler(CommandDictumHandler::class);
OldNWise::addHandler(MessageNotHandledHandle::class);

OldNWise::addHandler(CallbackMoreHandler::class);
OldNWise::addHandler(CallbackAuthorHandler::class);

OldNWise::run();