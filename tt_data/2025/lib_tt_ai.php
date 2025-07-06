<?php

function get_shuffled_slots() {
    $days = range(1, 6);    // 6 days a week
    $periods = range(1, 8); // 8 periods per day
    shuffle($days);
    shuffle($periods);
    return [$days, $periods];
}
function sort_teachers_by_least_load($teachers) {
    usort($teachers, function($a, $b) {
        return $a['bind'] <=> $b['bind'];
    });
    return $teachers;
}
function fullfill_bind_ai($action, $subject, $class, $ccode_c) {
    global $dmap;

    if (!isset($dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'])) {
        $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] = 0;
    }

    $needed = $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] - 
              $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'];

    if ($needed > 0) {
        echox(0, "Need to bind $needed slot(s) for $class:$subject\n");

        list($days, $periods) = get_shuffled_slots();
        $teachers = sort_teachers_by_least_load($dmap['teacher']['define']);

        foreach ($periods as $ip) {
            foreach ($days as $id) {
                foreach ($teachers as $teacherData) {
                    $teacher = $teacherData['name'];
                    $ccode_t = find_teacher($teacher)['value'];
                    $teacher_match = false;

                    for ($j2 = 0; $j2 < $teacherData['subjects']['count']; $j2++) {
                        $subjInfo = $teacherData['subjects'][$j2];
                        if (($action == "anyclass" || $subjInfo['subject'] == $subject) && $subjInfo['class'] == $class) {
                            $teacher_match = true;
                            break;
                        }
                    }

                    if (($action == "any" || $teacher_match) &&
                        $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['bind'] < 
                        $dmap['class']['define'][$ccode_c]['subjects']['map'][$subject]['count'] &&
                        $teacherData['bind'] < $teacherData['limit']) {

                        $success = bind_period($class, $teacher, $subject, $id, $ip);
                        if ($success) break 3; // Slot bound, break out of all loops
                    }
                }
            }
        }
    }
}
function auto_bind_ai($action) {
    global $dmap;

    echox(0, "Starting auto_bind_ai...\n");

    for ($i = 0; $i < $dmap['class']['count']; $i++) {
        $class = $dmap['class']['define'][$i]['name'];

        for ($i2 = 0; $i2 < $dmap['class']['define'][$i]['subjects']['count']; $i2++) {
            $subject = $dmap['class']['define'][$i]['subjects'][$i2]['subject'];
            fullfill_bind_ai($action, $subject, $class, $i);
        }
    }
}

?>
