<?php
/**
 * User Controller
 * Handle user profile and account management
 */

class UserController extends BaseController
{
    private $userModel;
    private $tarotModel;

    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->userModel = new User($database);
        $this->tarotModel = new TarotReading($database);

        // Require authentication for all methods
        $this->requireAuth();
    }

    /**
     * Show user profile
     */
    public function profile($params = [])
    {
        try {
            $user = $this->getCurrentUser();

            // Get user's reading history
            $readings = $this->tarotModel->getUserReadings($user['id'], 10);

            // Get user statistics
            $stats = $this->getUserStats($user['id']);

            $data = [
                'page_title' => 'Profilim - ' . APP_NAME,
                'meta_description' => 'Kişisel profil ve tarot okuma geçmişi',
                'user' => $user,
                'readings' => $readings,
                'stats' => $stats,
                'csrf_token' => $this->generateCSRFToken()
            ];

            $this->view('user.profile', $data);

        } catch (Exception $e) {
            error_log('Profile Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->redirect('/profile', 'Güvenlik hatası. Lütfen tekrar deneyin.', 'error');
        }

        $user = $this->getCurrentUser();

        $errors = $this->request->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'birth_date' => 'required',
            'birth_time' => 'nullable',
            'birth_place' => 'nullable|max:100'
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $this->request->all();
            $this->redirect('/profile');
        }

        try {
            $updateData = [
                'first_name' => $this->sanitize($this->request->input('first_name')),
                'last_name' => $this->sanitize($this->request->input('last_name')),
                'birth_date' => $this->request->input('birth_date'),
                'birth_time' => $this->request->input('birth_time'),
                'birth_place' => $this->sanitize($this->request->input('birth_place'))
            ];

            $result = $this->userModel->updateProfile($user['id'], $updateData);

            if ($result) {
                $this->redirect('/profile', 'Profil başarıyla güncellendi.', 'success');
            } else {
                $this->redirect('/profile', 'Profil güncellenirken bir hata oluştu.', 'error');
            }

        } catch (Exception $e) {
            error_log('Update Profile Error: ' . $e->getMessage());
            $this->redirect('/profile', 'Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
        }
    }

    /**
     * Show public user profile
     */
    public function publicProfile($params = [])
    {
        try {
            $username = $params['username'] ?? '';

            if (empty($username)) {
                $this->redirect('/', 'Kullanıcı bulunamadı.', 'error');
            }

            $user = $this->userModel->findByUsername($username);

            if (!$user) {
                $this->redirect('/', 'Kullanıcı bulunamadı.', 'error');
            }

            // Get user's public readings
            $readings = $this->tarotModel->getPublicReadingsByUser($user['id'], 10);

            $data = [
                'page_title' => $user['first_name'] . ' ' . $user['last_name'] . ' - ' . APP_NAME,
                'meta_description' => $user['first_name'] . ' kullanıcısının profil sayfası',
                'user' => $user,
                'readings' => $readings
            ];

            $this->view('user.public-profile', $data);

        } catch (Exception $e) {
            error_log('Public Profile Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }

    /**
     * Get user statistics
     */
    private function getUserStats($userId)
    {
        // Get total readings count
        $this->db->query('SELECT COUNT(*) as total FROM tarot_readings WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $totalReadings = $this->db->fetch()['total'];

        // Get this month's readings
        $this->db->query('SELECT COUNT(*) as monthly FROM tarot_readings WHERE user_id = :user_id AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
        $this->db->bind(':user_id', $userId);
        $monthlyReadings = $this->db->fetch()['monthly'];

        // Get favorite spread type
        $this->db->query('SELECT spread_type, COUNT(*) as count FROM tarot_readings WHERE user_id = :user_id GROUP BY spread_type ORDER BY count DESC LIMIT 1');
        $this->db->bind(':user_id', $userId);
        $favoriteSpread = $this->db->fetch();

        return [
            'total_readings' => $totalReadings,
            'monthly_readings' => $monthlyReadings,
            'favorite_spread' => $favoriteSpread ? $favoriteSpread['spread_type'] : null
        ];
    }
}