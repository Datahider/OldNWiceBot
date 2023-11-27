<?php

namespace losthost\OldNWise\model;
use losthost\DB\DBObject;
use losthost\DB\DB;


class dictum_author extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(64) NOT NULL',
        'description' => 'VARCHAR(1024) NOT NULL',
        'PRIMARY KEY' => 'id'
    ];
    
    protected static function createTable() {
        parent::createTable();
        
        DB::exec(<<<END
            INSERT INTO [dictum_author] 
                VALUES 
                    (1,'Автор неизвестен','Если вы знаете автора этого высказывания, пожалуйста сообщите мне в телеграм @progmagic'),
                    (2,'Лао-Цзы','древнекитайский философ VI-V веков до н. э., которому приписывается авторство классического даосского философского трактата «Дао Дэ Цзин». В рамках современной исторической науки историчность Лао-цзы подвергается сомнению, тем не менее в научной литературе он часто всё равно определяется как основоположник даосизма. В религиозно-философском учении большинства даосских школ Лао-цзы традиционно почитается как божество — один из Трёх Чистых.'),
                    (3,'Альберт Эйнштейн','физик-теоретик, один из основателей современной теоретической физики, лауреат Нобелевской премии по физике 1921 года, общественный деятель-гуманист.'),
                    (4,'Аристотель','древнегреческий философ. Ученик Платона. С 343 года до н. э. по 340 года до н. э. — воспитатель Александра Македонского. В 335/4 годах до н. э. основал Ликей (др.-греч. Λύκειον Лицей, или перипатетическую школу). Натуралист классического периода. Наиболее влиятельный из философов древности; основоположник формальной логики. Создал понятийный аппарат, который до сих пор пронизывает философский лексикон и стиль научного мышления, заложил основы современных естественных наук'),
                    (5,'Джон Леннон','британский рок-музыкант, певец, поэт, композитор, художник, писатель и активист. Один из основателей и участник группы The Beatles. Является одним из самых популярных музыкантов XX века. После распада The Beatles начал сольную карьеру, но в 1980 году был убит бывшим фанатом группы.'),
                    (6,'Борис Стругацкий','русский советский писатель, сценарист, переводчик, создавший в соавторстве с братом Аркадием Стругацким несколько десятков произведений, ставших классикой современной научной и социальной фантастики. После того, как в 1991 году умер его брат и соавтор А. Н. Стругацкий, опубликовал два самостоятельных романа.'),
                    (7,'Фёдор Достоевский','русский писатель, мыслитель, философ и публицист. Член-корреспондент Петербургской академии наук с 1877 года. Классик мировой литературы, по данным ЮНЕСКО, один из самых читаемых писателей в мире. Собрание сочинений Достоевского состоит из 12 романов, четырёх новелл, 16 рассказов и множества других произведений.'),
                    (8,'Марк Твен','американский писатель, юморист, журналист и общественный деятель. Его творчество охватывает множество жанров — юмор, сатиру, философскую фантастику, публицистику и другие, и во всех этих жанрах он неизменно занимает позицию гуманиста и демократа.'),
                    (9,'Вольтер','французский философ-просветитель XVIII века, поэт, прозаик, сатирик, трагик, историк и публицист.'),
                    (10,'Наполеон Бонапарт','император французов в 1804—1814 и 1815 годах, полководец и государственный деятель, заложивший основы современного французского государства, один из наиболее выдающихся деятелей в истории Запада.'),
                    (11,'Омар Хайям','персидский философ, математик, астроном и поэт.');    
            END);
    }
}
