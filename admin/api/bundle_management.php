<?php
/**
 * Course Bundle Management API
 * Handles AJAX requests for dynamic course bundle management
 */

header('Content-Type: application/json');
require_once '../../includes/db.php';
require_once '../auth_check.php';

$response = ['success' => false, 'message' => '', 'data' => null];

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'search_courses':
            $query = $_GET['q'] ?? '';
            $package_id = $_GET['package_id'] ?? 0;
            
            try {
                // Preferred: exclude via junction table
                $sql = "SELECT c.id, c.title, c.category, c.duration, c.rating 
                        FROM courses c 
                        WHERE c.status = 'active' 
                        AND c.title LIKE ? 
                        AND c.id NOT IN (
                            SELECT course_id FROM package_courses WHERE package_id = ?
                        )
                        ORDER BY c.title ASC 
                        LIMIT 10";
                $courses = fetchAll($sql, ['%' . $query . '%', $package_id]);
            } catch (Exception $e) {
                // Fallback: exclude by legacy direct mapping (courses.package_id)
                $sql = "SELECT c.id, c.title, c.category, c.duration, c.rating 
                        FROM courses c 
                        WHERE c.status = 'active' 
                        AND c.title LIKE ? 
                        AND (c.package_id IS NULL OR c.package_id != ?) 
                        ORDER BY c.title ASC 
                        LIMIT 10";
                $courses = fetchAll($sql, ['%' . $query . '%', $package_id]);
            }
            
            $response['success'] = true;
            $response['data'] = $courses;
            break;
            
        case 'get_package_courses':
            $package_id = $_GET['package_id'] ?? 0;
            
            try {
                // Combine junction-based and legacy-based relationships, excluding duplicates
                $sql = "
                    SELECT c.id, c.title, c.category, c.duration, c.rating, c.image, pc.sort_order
                    FROM courses c 
                    JOIN package_courses pc ON c.id = pc.course_id 
                    WHERE pc.package_id = ? AND c.status = 'active'
                    UNION ALL
                    SELECT c.id, c.title, c.category, c.duration, c.rating, c.image, c.sort_order AS sort_order
                    FROM courses c
                    WHERE c.status = 'active'
                      AND c.package_id = ?
                      AND NOT EXISTS (
                          SELECT 1 FROM package_courses pc 
                          WHERE pc.package_id = ? AND pc.course_id = c.id
                      )
                    ORDER BY sort_order ASC, title ASC
                ";
                $courses = fetchAll($sql, [$package_id, $package_id, $package_id]);
            } catch (Exception $e) {
                $courses = [];
            }
            
            $response['success'] = true;
            $response['data'] = $courses;
            break;
            
        case 'add_course_to_package':
            $package_id = $_POST['package_id'] ?? 0;
            $course_id = $_POST['course_id'] ?? 0;
            
            if ($package_id && $course_id) {
                try {
                    // Get next sort order from junction table
                    $max_order = fetchOne("SELECT MAX(sort_order) as max_order FROM package_courses WHERE package_id = ?", [$package_id]);
                    $sort_order = ($max_order['max_order'] ?? 0) + 1;
                    
                    // Insert relationship
                    $sql = "INSERT INTO package_courses (package_id, course_id, sort_order) VALUES (?, ?, ?)";
                    executeQuery($sql, [$package_id, $course_id, $sort_order]);
                } catch (Exception $e) {
                    // Fallback: set the course's package_id directly (legacy mode)
                    executeQuery("UPDATE courses SET package_id = ? WHERE id = ?", [$package_id, $course_id]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Course added to package successfully';
            } else {
                $response['message'] = 'Invalid package or course ID';
            }
            break;
            
        case 'remove_course_from_package':
            $package_id = $_POST['package_id'] ?? 0;
            $course_id = $_POST['course_id'] ?? 0;
            
            if ($package_id && $course_id) {
                try {
                    $sql = "DELETE FROM package_courses WHERE package_id = ? AND course_id = ?";
                    executeQuery($sql, [$package_id, $course_id]);
                } catch (Exception $e) {
                    // Fallback: clear legacy mapping
                    executeQuery("UPDATE courses SET package_id = NULL WHERE id = ? AND package_id = ?", [$course_id, $package_id]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Course removed from package successfully';
            } else {
                $response['message'] = 'Invalid package or course ID';
            }
            break;
            
        case 'update_course_order':
            $package_id = $_POST['package_id'] ?? 0;
            $course_orders = $_POST['course_orders'] ?? [];
            // If course_orders is a JSON string, decode it
            if (is_string($course_orders)) {
                $course_orders = json_decode($course_orders, true);
            }
            if ($package_id && !empty($course_orders) && is_array($course_orders)) {
                foreach ($course_orders as $course_id => $sort_order) {
                    $sql = "UPDATE package_courses SET sort_order = ? WHERE package_id = ? AND course_id = ?";
                    executeQuery($sql, [$sort_order, $package_id, $course_id]);
                }
                $response['success'] = true;
                $response['message'] = 'Course order updated successfully';
            } else {
                $response['message'] = 'Invalid data provided';
            }
            break;
            
        case 'get_all_courses':
            $package_id = $_GET['package_id'] ?? 0;
            
            // Get all courses not in this package
            $sql = "SELECT c.id, c.title, c.category, c.duration, c.rating 
                    FROM courses c 
                    WHERE c.status = 'active' 
                    AND c.id NOT IN (
                        SELECT course_id FROM package_courses WHERE package_id = ?
                    )
                    ORDER BY c.category ASC, c.title ASC";
            
            $courses = fetchAll($sql, [$package_id]);
            
            $response['success'] = true;
            $response['data'] = $courses;
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Bundle Management API Error: " . $e->getMessage());
}

echo json_encode($response);
