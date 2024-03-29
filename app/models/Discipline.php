<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
    class Discipline{
        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function getDatabase() {
            return $this->db;
        }

        public function getDisciplines(){
            $this->db->query("SELECT * FROM disciplines");

            $results = $this->db->resultSet();

            return $results;
        }

        public function getDisciplineById($id){
            $this->db->query("SELECT * FROM disciplines WHERE id = :id");
            $this->db->bind(':id', $id);

            $row = $this->db->single();

            return $row;
        }

        public function getUsersByDisciplineId($id){
            $this->db->query("SELECT * FROM usersDisciplines WHERE disciplineId = :id");
            $this->db->bind(':id', $id);

            $results = $this->db->resultSet();

            return $results;
        }

        public function getDisciplinesByUserId($id)
        {
            $this->db->query("SELECT d.* from disciplines d JOIN users_disciplines ud ON d.id = ud.disciplineId WHERE ud.userId = :id");
            $this->db->bind(':id', $id);

            $results = $this->db->resultSet();

            return $results;
        }

        public function getDisciplinesIdsByUserId($id)
        {
            $this->db->query("SELECT disciplineId FROM users_disciplines WHERE userId = :id");
            $this->db->bind(':id', $id);

            $results = $this->db->resultSet();

            return $results;
        }

        public function addDiscipline($data){
                $this->db->query("INSERT INTO disciplines (
                disciplineNameBg
                ,disciplineNameEng
                ,specialtiesAndCourses
                ,category
                ,oks
                ,professor
                ,semester
                ,elective
                ,credits
                ,annotation
                ,prerequisites
                ,expectations
                ,content
                ,synopsis
                ,bibliography
                ,code
                ,administrativeInfo
                )
                VALUES (
                :disciplineNameBg,
                :disciplineNameEng,
                :specialtiesAndCourses,
                :category,
                :oks,
                :professor,
                :semester,
                :elective,
                :credits,
                :annotation,
                :prerequisites,
                :expectations,
                :content,
                :synopsis,
                :bibliography,
                :code,
                :administrativeInfo)");

                $this->db->bind(':disciplineNameBg', $data['disciplineNameBg']);
                $this->db->bind(':disciplineNameEng', $data['disciplineNameEng']);
                $this->db->bind(':specialtiesAndCourses', $data['specialtiesAndCourses']);
                $this->db->bind(':category', $data['category']);
                $this->db->bind(':oks', $data['oks']);
                $this->db->bind(':professor', $data['professor']);
                $this->db->bind(':semester', $data['semester']);
                $this->db->bind(':elective', $data['elective']);
                $this->db->bind(':credits', $data['credits']);
                $this->db->bind(':annotation', $data['annotation']);
                $this->db->bind(':prerequisites', $data['prerequisites']);
                $this->db->bind(':expectations', $data['expectations']);
                $this->db->bind(':content', $data['content']);
                $this->db->bind(':synopsis', $data['synopsis']);
                $this->db->bind(':bibliography', $data['bibliography']);
                $this->db->bind(':code', $data['code']);
                $this->db->bind(':administrativeInfo', $data['administrativeInfo']);

            
                // Execute
                if($this->db->execute()){
                    return true;
                } else {
                    echo "fail";
                    return false;
                }
        }

        public function updateDiscipline($data){
            $this->db->query("UPDATE disciplines SET 
            disciplineNameBg = :disciplineNameBg,
            disciplineNameEng = :disciplineNameEng,
            specialtiesAndCourses = :specialtiesAndCourses,
            category = :category,
            oks = :oks,
            professor = :professor,
            semester = :semester,
            elective = :elective,
            credits = :credits,
            annotation = :annotation,
            prerequisites = :prerequisites,
            expectations = :expectations,
            content = :content,
            synopsis = :synopsis,
            bibliography = :bibliography,
            code = :code,
            administrativeInfo = :administrativeInfo
            WHERE id = :id");

            $this->db->bind(':disciplineNameBg', $data['disciplineNameBg']);
            $this->db->bind(':disciplineNameEng', $data['disciplineNameEng']);
            $this->db->bind(':specialtiesAndCourses', $data['specialtiesAndCourses']);
            $this->db->bind(':category', $data['category']);
            $this->db->bind(':oks', $data['oks']);
            $this->db->bind(':professor', $data['professor']);
            $this->db->bind(':semester', $data['semester']);
            $this->db->bind(':elective', $data['elective']);
            $this->db->bind(':credits', $data['credits']);
            $this->db->bind(':annotation', $data['annotation']);
            $this->db->bind(':prerequisites', $data['prerequisites']);
            $this->db->bind(':expectations', $data['expectations']);
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':synopsis', $data['synopsis']);
            $this->db->bind(':bibliography', $data['bibliography']);
            $this->db->bind(':code', $data['code']);
            $this->db->bind(':administrativeInfo', $data['administrativeInfo']);
            $this->db->bind(':id', $data['id']);


            // Execute
            if($this->db->execute()){
                return true;
            } else {
                echo "fail";
                return false;
            }
    }

        public function addDisciplineForCurriculum($data){
            $this->db->query("INSERT IGNORE INTO `curriculum_disciplines` (disciplineId, curriculumId)
            VALUES (:disciplineId, :curriculumId)");

            $this->db->bind(':disciplineId', $data['disciplineId']);
            $this->db->bind(':curriculumId', $data['curriculumId']);
            
            // Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        public function addDisciplineDependsOn($data){
            $this->db->query("INSERT IGNORE INTO `depends_on` (disciplineId, code)
            VALUES (:disciplineId, :code)");

            $this->db->bind(':disciplineId', $data['disciplineId']);
            $this->db->bind(':code', $data['code']);
            
            // Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        public function addDisciplinesDependBy($data){
            $this->db->query("INSERT IGNORE INTO `depend_by` (disciplineId, code)
            VALUES (:disciplineId, :code)");

            $this->db->bind(':disciplineId', $data['disciplineId']);
            $this->db->bind(':code', $data['code']);
            
            // Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        public function getDisciplineInfoByCode($code){

            // Check if discipline with such code exists. It is possible that we upload a dependancy between
            // a discipline that has not yet been uploaded. A constraint between depends_on and depend_by and disciplines
            // is not added for simplicity's sake and due to the implementation.

            $this->db->query("SELECT COUNT(*)  FROM disciplines WHERE code='$code'");
            $count = $this->db->single();

            if($count != 0){
                $this->db->query("SELECT * FROM disciplines WHERE code=:code ");
                $this->db->bind(':code', $code);
                $row = $this->db->single();
                return $row;
            }
            return NULL;
        }

        public function getDisciplineDependsOn($id){
            $this->db->query("SELECT code FROM depends_on WHERE disciplineId=$id");
            $results = $this->db->resultSet();
            return $results;
        }

        public function getDisciplineDependBy($id){
            $this->db->query("SELECT code FROM depend_by WHERE disciplineId=$id");
            $results = $this->db->resultSet();
            return $results;
        }

        public function enroll($userId, $disciplineId) {
            $this->db->query('INSERT INTO users_disciplines (userId, disciplineId) VALUES (:userId, :disciplineId)');

            $this->db->bind(':userId', $userId);
            $this->db->bind(':disciplineId', $disciplineId);

            if($this->db->execute()){
                return true;
            }
            return false;
        }

        public function unenroll($userId, $disciplineId) {
            $this->db->query('DELETE FROM users_disciplines WHERE userId = :userId AND disciplineId = :disciplineId');

            $this->db->bind(':userId', $userId);
            $this->db->bind(':disciplineId', $disciplineId);

            if($this->db->execute()){
                return true;
            }
            return false;
        }

        public function search($field, $searchInput){
            $this->db->query("SELECT * FROM disciplines WHERE $field LIKE '%$searchInput%'");
            
            /*Binding parameters in a query like this is buggy, so we avoid it here in the name of working search functionality */
            /*To assure some security we sanitize $_POST input in the controller search method */

            $results = $this->db->resultSet();

            return $results;
        }

        public function searchDisciplines($searchCriteria) {
            $whereConditions = [];
            $fieldsValues = [];
            $query = "SELECT * FROM `disciplines` d";

            if ($searchCriteria['rec'] === 'on') {
                $query = "SELECT * FROM disciplines d JOIN users_details ud ON d.specialtiesAndCourses LIKE CONCAT('%', ud.specialtiesAndCourses, '%') ";
            }

            foreach ($searchCriteria as $field => $value) {
                if (!empty($value) && $field !== 'rec' && $field !== 'recommended') {
                    if ($field === 'userId') {
                        if ($searchCriteria['rec'] === 'on') {
                            $whereConditions[] = is_numeric($value) ? "ud.$field = ?" : "ud.$field LIKE ?";
                            $fieldsValues[] = is_numeric($value) ? $value : "%$value%";
                        }
                    } else if ($field === 'keyword' && $value !== '') {
                        $whereConditions[] = "(d.disciplineNameBg LIKE ? OR d.disciplineNameEng LIKE ? OR d.specialtiesAndCourses LIKE ? OR d.category LIKE ? OR d.oks LIKE ? OR d.professor LIKE ? OR d.semester LIKE ? OR d.elective LIKE ? OR d.annotation LIKE ? OR d.prerequisites LIKE ? OR d.expectations LIKE ? OR d.content LIKE ? OR d.bibliography LIKE ? OR d.code LIKE ?)";

                        for ($i = 1; $i <= 14; $i++) {
                            $fieldsValues[] = is_numeric($value) ? "%$value%" : "%$value%";
                        }
                    } else {
                        $whereConditions[] = is_numeric($value) ? "d.$field = ?" : "d.$field LIKE ?";
                        $fieldsValues[] = is_numeric($value) ? $value : "%$value%";
                    }
                }
            }

            $whereClause = implode(' AND ', $whereConditions);

            if (!empty($whereConditions)) {
                $query .= " WHERE $whereClause";
            }

            $this->db->query($query);

            foreach ($fieldsValues as $key => $value) {
                $this->db->bind($key + 1, $value);
            }

            $this->db->execute();
            $results = $this->db->resultSet();

            return $results;
        }

        public function searchDisciplinesByUserId($searchCriteria, $userId) {
            $whereConditions = [];
            $fieldsValues = [];

            foreach ($searchCriteria as $field => $value) {
                if (!empty($value)) {
                    $whereConditions[] = is_numeric($value) ? "$field = ?" : "$field LIKE ?";
                    $fieldsValues[] = is_numeric($value) ? $value : "%$value%";
                }
            }

            $whereClause = implode(' AND ', $whereConditions);
            $query = "SELECT d.* FROM disciplines d JOIN users_disciplines ud ON d.id = ud.disciplineId WHERE ud.userId = ?";

            if (!empty($whereConditions)) {
                $query .= " AND $whereClause";
            }
            $this->db->query($query);

            $this->db->bind(1, $userId);
            foreach ($fieldsValues as $key => $value) {
                $this->db->bind($key + 2, $value);
            }

            $this->db->execute();
            $results = $this->db->resultSet();

            return $results;
        }

        public function getUsersByDisciplinesData() {
            $this->db->query("SELECT u.name, count(ud.disciplineId) as `count` from users u JOIN users_disciplines ud ON u.id = ud.userId GROUP BY u.name;");

            $results = $this->db->resultSet();

            return $results;
        }

        public function getDisciplinesByUsersData() {
            $this->db->query("SELECT d.disciplineNameBg, count(ud.userId) as `count` from disciplines d JOIN users_disciplines ud ON d.id = ud.disciplineId GROUP BY d.disciplineNameBg;");

            $results = $this->db->resultSet();

            return $results;
        }

        public function getLastInserted(){
            $id = $this->db->getLastInsertedId();
            return $id;
        }

        /* Before we delete a discipline from the DB we need to delete its occurences in relationships tables */
        public function deleteDisciplineCurriculumRelationship($id){
            $this->db->query('DELETE FROM `curriculum_disciplines` WHERE disciplineId = :id');
            
            $this->db->bind(':id', $id);

            if($this->db->execute()){
               return true;
            } else {
                return false;
            }
        }

        public function deleteDisciplineDependsOn($id){
            $this->db->query('DELETE FROM `depends_on` WHERE disciplineId = :id');
            
            $this->db->bind(':id', $id);

            if($this->db->execute()){
               return true;
            } else {
                return false;
            }
        }

        public function deleteDisciplineDependBy($id){
            $this->db->query('DELETE FROM `depend_by` WHERE disciplineId = :id');
            
            $this->db->bind(':id', $id);

            if($this->db->execute()){
               return true;
            } else {
                return false;
            }
        }

        public function deleteDiscipline($id){
            $this->deleteDisciplineCurriculumRelationship($id);
            $this->deleteDisciplineDependsOn($id);
            $this->deleteDisciplineDependBy($id);

            $this->db->query('DELETE FROM `disciplines` WHERE id = :id');
            
            $this->db->bind(':id', $id);

            if($this->db->execute()){
                $file ="../public/JSONS/file" . $id . ".json";
                if(file_exists($file)) { 
                    if(unlink($file)){
                        return true;
                    }
                }
            } else {
                return false;
            }
            return true;
        }
    }
?>