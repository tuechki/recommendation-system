<?php
$fields = [
    "specialtiesAndCourses" => "Специалност ",
    "category" => "Категория   ",
    "oks" => "ОКС         ",
    "elective" => "Статут      ",
    "credits" => "Кредити     ",
    "semester" => "Семестър    ",
    "disciplineNameBg" => "Дисциплна   ",
    "professor" => "Преподавател",
    "code" => "Код         "
];
?>

<div class="disciplinesAllContainer">
    <div class="topContainer">
        <div class="title">
            <h1>Записани дисциплини</h1>
        </div>

        <div id="searchDiv">
            <form id="disciplinesSearchForm" action="<?php echo URLROOT; ?>/disciplines/enrolled" method="post">
                <?php
                $counter = 0;
                foreach ($fields as $field => $label):
                    $newField = $field . "-new";
                    if ($counter % 3 === 0) {
                        echo '<div class="field-group">';
                    }
                    ?>
                    <div class="field-container">
                        <label for="<?php echo $field; ?>"><?php echo $label; ?>:</label>
                        <?php if ($field === "category"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $newField; ?>">
                                <option value="">-- Изберете категория --</option>
                                <option value="ОКН" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'ОКН') ? 'selected' : ''; ?>>ОКН</option>
                                <option value="ЯКН" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'ЯКН') ? 'selected' : ''; ?>>ЯКН</option>
                                <option value="М" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'М') ? 'selected' : ''; ?>>М</option>
                            </select>
                        <?php elseif ($field === "oks"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>">
                                <option value="">-- Изберете ОКС --</option>
                                <option value="Бакалавър" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Бакалавър') ? 'selected' : ''; ?>>Бакалавър</option>
                                <option value="Магистър" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Магистър') ? 'selected' : ''; ?>>Магистър</option>
                            </select>
                        <?php elseif ($field === "elective"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $newField; ?>">
                                <option value="">-- Изберете статут --</option>
                                <option value="Задължителна" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Задължителна') ? 'selected' : ''; ?>>Задължителна</option>
                                <option value="Избираема" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Избираема') ? 'selected' : ''; ?>>Избираема</option>
                            </select>
                        <?php elseif ($field === "credits"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $newField; ?>">
                                <option value="">-- Изберете кредити --</option>
                                <?php for ($i = 3; $i <= 9; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($_POST[$field]) && $_POST[$field] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        <?php elseif ($field === "semester"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>">
                                <option value="">-- Изберете семестър --</option>
                                <option value="Летен" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Летен') ? 'selected' : ''; ?>>Летен</option>
                                <option value="Зимен" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Зимен') ? 'selected' : ''; ?>>Зимен</option>
                            </select>
                        <?php elseif ($field === "specialtiesAndCourses"): ?>
                            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>">
                                <option value="">-- Изберете специалност --</option>
                                <option value="Компютърни науки" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Компютърни науки') ? 'selected' : ''; ?>>Компютърни науки</option>
                                <option value="Софтуерно инженерство" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Софтуерно инженерство') ? 'selected' : ''; ?>>Софтуерно инженерство</option>
                                <option value="Математика" <?php echo (isset($_POST[$field]) && $_POST[$field] === 'Математика') ? 'selected' : ''; ?>>Математика</option>
                            </select>
                        <?php else: ?>
                            <input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : ''; ?>">
                        <?php endif; ?>
                    </div>
                    <?php
                    if ($counter % 3 === 2) {
                        echo '</div>';
                    }
                    $counter++;
                endforeach;
                // Check if the last group is incomplete
                if ($counter % 3 !== 0) {
                    echo '</div>';
                }
                ?>

                <div id="buttonDiv">
                    <input type="submit" value="Намери дисциплина">
                </div>

                <input type="hidden" name="jsonFields" id="jsonFields">
            </form>


            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelector('#disciplinesSearchForm').addEventListener('submit', function (event) {
                        event.preventDefault();

                        // Get form values and create an object
                        var formData = {};
                        var formElements = this.elements;

                        for (var i = 0; i < formElements.length; i++) {
                            var field = formElements[i];

                            // Skip buttons and other non-input elements
                            if (field.type !== 'submit' && field.name !== "jsonFields") {
                                formData[field.name] = field.value;
                                formData[field.name] = /^\d+$/.test(field.value) ? parseInt(field.value, 10) : field.value;
                            }

                        }

                        // Set the value of the hidden input field to the JSON representation of formData
                        document.getElementById('jsonFields').value = encodeURIComponent(JSON.stringify(formData));

                        // Submit the form
                        this.submit();
                    });
                });
            </script>

        </div>

        <?php if($data['searchedField']){?>
            <div id="displayLastQuery">
                <p>Търсене за дисциплина -  <?php echo $data['searchedField']; ?> : <?php echo $data['searchedValue'];?></p>
            </div>
        <?php } ?>
    </div>
</div>

<?php if($data['no_results_message']){?>
    <div id="noResults">
        <p><?php echo $data['no_results_message']; ?></p>
    </div>
<?php } else {?>
        <div class="curriculumList">
            <?php
            foreach($data['disciplines'] as $discipline) :
                ?>
                <div class="discipline-container curriculumRow">
                    <a class="commonLink" href="<?php echo URLROOT . "/disciplines/visualise/" . $discipline->id;?>"><?php echo $discipline->disciplineNameBg;?></a>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] != 'admin') : ?>
                        <form action="<?php echo URLROOT . "/disciplines/unenroll/" ?>" method="POST">
                            <input type="hidden" name="disciplineId" value="<?php echo $discipline->id; ?>">
                            <input type="hidden" name="userId" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="unenroll-button-container">
                                <button name="unenroll_button" class="unenroll-button">
                                    Отпиши
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        $enrollmentMessage = $data['enrollmentMessage'];
        $unenrollmentMessage = $data['unenrollmentMessage'];
        ?>
        <input type="hidden" id="enrollmentMessageHidden" value="<?php echo htmlspecialchars($enrollmentMessage, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" id="unenrollmentMessageHidden" value="<?php echo htmlspecialchars($unenrollmentMessage, ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>
</div>

<div id="enrollmentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeЕnrollmentModal()">&times;</span>
        <p id="enrollmentMessage"></p>
    </div>
</div>

<div id="unenrollmentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUnenrollmentModal()">&times;</span>
        <p id="unenrollmentMessage"></p>
    </div>
</div>
</body>
<script type="text/javascript">
    // Retrieve and display the enrollment status message
    var enrollmentMessage = document.getElementById("enrollmentMessageHidden").value;
    if (enrollmentMessage != "") {
        openЕnrollmentModal(enrollmentMessage);
    }

    function openЕnrollmentModal(message) {
        var modal = document.getElementById("enrollmentModal");
        var messageElement = document.getElementById("enrollmentMessage");

        messageElement.innerHTML = message;
        modal.style.display = "block";
    }

    function closeЕnrollmentModal() {
        var modal = document.getElementById("enrollmentModal");
        modal.style.display = "none";
    }

    // Retrieve and display the enrollment status message
    var unenrollmentMessage = document.getElementById("unenrollmentMessageHidden").value;
    if (unenrollmentMessage != "") {
        openUnenrollmentModal(unenrollmentMessage);
    }

    function openUnenrollmentModal(message) {
        var modal = document.getElementById("unenrollmentModal");
        var messageElement = document.getElementById("unenrollmentMessage");

        messageElement.innerHTML = message;
        modal.style.display = "block";
    }

    function closeUnenrollmentModal() {
        var modal = document.getElementById("unenrollmentModal");
        modal.style.display = "none";
    }
</script>
<script src="<?php echo URLROOT; ?>/js/search.js"></script>
