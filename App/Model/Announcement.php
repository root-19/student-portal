
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/autoload.php'; 
require_once __DIR__  . '/../Database/Database.php';

class Announcement {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    public function createAnnouncement($title, $content, $userId) {
        try {
            $query = "INSERT INTO announcements (title, content, date_created, user_id) VALUES (:title, :content, NOW(), :user_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':user_id', $userId);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert announcement.");
            }
            
            $announcementId = $this->db->lastInsertId();
    
            if (!$announcementId) {
                throw new Exception("Failed to retrieve last inserted ID.");
            }
    
            // Notify users
            $this->notifyUsers($announcementId, $title, $content);
    
            return $announcementId;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    

    private function notifyUsers($announcementId, $title, $content) {
        // Get all users with the role 'user'
        $query = "SELECT email FROM users WHERE role = 'user'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Create an instance of PHPMailer
        $mail = new PHPMailer(true);

        // Set mailer to use SMTP
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'hperformanceexhaust@gmail.com';
            $mail->Password = 'wolv wvyy chhl rvvm';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set sender information
            $mail->setFrom('your_email@example.com', 'Admin');
            $mail->isHTML(true);

            // Loop through all users and send them the email
            foreach ($users as $user) {
                $mail->addAddress($user['email']);

                // Email content
                $mail->Subject = "New Announcement: $title";
                $mail->Body    = "<h3>$title</h3><p>$content</p><p>Read the full announcement</p>";

                // Send email
                $mail->send();
                $mail->clearAddresses(); // Clear previous recipient
                // $mail->SMTPDebug = 2;
            }

            echo 'Emails have been sent.';
        } catch (Exception $e) {
            echo '<div class="text-center text-red-600 mt-4">Error: ' . $e->getMessage() . '</div>';
        }
        
    }

    public function getAnnouncementsWithReactions() {
        $query = "
          SELECT a.announcement_id, a.title, a.content, a.date_created, 
       COALESCE(SUM(r.reaction_type = 'haha'), 0) AS haha,
       COALESCE(SUM(r.reaction_type = 'angry'), 0) AS angry,
       COALESCE(SUM(r.reaction_type = 'love'), 0) AS love,
       COALESCE(SUM(r.reaction_type = 'like'), 0) AS like_count
FROM announcements a
LEFT JOIN reactions r ON a.announcement_id = r.announcement_id
GROUP BY a.announcement_id 
ORDER BY a.date_created DESC;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reactToAnnouncement($announcementId, $userId, $reactionType) {
        $query = "
            INSERT INTO reactions (announcement_id, user_id, reaction_type)
            VALUES (:announcement_id, :user_id, :reaction_type)
            ON DUPLICATE KEY UPDATE reaction_type = :reaction_type";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':announcement_id', $announcementId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':reaction_type', $reactionType);
        $stmt->execute();
    }
}
