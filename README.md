# MOCK CDI IELTS Test Platform

A comprehensive web-based platform for creating and administering IELTS mock tests with advanced security features and anti-cheating measures.

## Features

### For Administrators
- **Test Management**: Create, edit, and manage IELTS tests with custom timing
- **Material Upload**: Upload audio files for listening tests and text passages for reading
- **Question Creation**: Add multiple choice, gap filling, and select options questions
- **Results Analytics**: View detailed student performance and test statistics
- **Security Monitoring**: Track potential cheating attempts and session violations

### For Students
- **Secure Testing Environment**: Full-screen mode with comprehensive anti-cheating measures
- **Real Exam Conditions**: Authentic IELTS test structure with proper timing
- **Interactive Interface**: 
  - Listening: Audio player with no pause/replay controls
  - Reading: Text highlighting and passage navigation
  - Writing: Word count tracking and auto-save functionality
- **Progress Tracking**: Real-time progress monitoring and module completion

### Security Features
- **Full-screen Enforcement**: Tests run in mandatory full-screen mode
- **Anti-cheating Measures**:
  - Keyboard shortcut prevention (Ctrl+C, Ctrl+V, F5, etc.)
  - Right-click context menu blocking
  - Text selection prevention
  - Tab switching detection
  - Window focus monitoring
  - Audio download prevention
- **Session Management**: Secure session tokens and automatic timeout
- **Material Protection**: No download capabilities for test materials

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 4.0, Alpine.js
- **Database**: MySQL/PostgreSQL
- **File Storage**: Laravel Storage (local/cloud)
- **Build Tool**: Vite

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd mock-cdi
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mock_cdi
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database with sample data**
   ```bash
   php artisan db:seed --class=TestSeeder
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Set up file storage**
   ```bash
   php artisan storage:link
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

## Usage

### Admin Panel

1. **Access admin dashboard**: Navigate to `/admin`
2. **Create a new test**:
   - Set test title and description
   - Configure timing for each module
   - Set test status (draft/active/inactive)
3. **Add materials**:
   - Upload audio files for listening tests
   - Add text passages for reading tests
   - Create writing task descriptions
4. **Create questions**:
   - Add multiple choice questions
   - Create gap filling exercises
   - Set up select options questions
5. **Publish the test** when ready

### Student Testing

1. **Registration**: Students register with personal information
2. **Test Session**: Secure session creation with unique tokens
3. **Module Progression**:
   - **Listening**: Audio playback with questions by parts
   - **Reading**: Passage navigation with highlighting tools
   - **Writing**: Two writing tasks with word count tracking
4. **Completion**: Automatic module progression and test completion

## Database Structure

### Core Tables
- `tests`: Test configuration and settings
- `materials`: Audio files, text passages, and writing tasks
- `questions`: Test questions with various types
- `students`: Student registration information
- `test_sessions`: Active test sessions and progress
- `student_responses`: Individual question responses
- `writing_responses`: Writing task submissions

### Key Relationships
- Tests have multiple materials and questions
- Students create test sessions for specific tests
- Responses are linked to sessions, students, and questions
- Materials are organized by module and part

## Security Implementation

### Anti-Cheating Measures
```javascript
// Keyboard shortcut prevention
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) || 
        e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
        e.preventDefault();
        return false;
    }
});

// Full-screen enforcement
document.addEventListener('fullscreenchange', function() {
    if (!document.fullscreenElement) {
        recordCheatAttempt('Exited fullscreen mode');
    }
});
```

### Session Security
- Unique session tokens for each test attempt
- Automatic session timeout
- Cheat attempt logging and monitoring
- Secure material delivery

## Customization

### Adding New Question Types
1. Update the `Question` model's `type` enum
2. Add question type logic in the `checkAnswer` method
3. Create corresponding view components
4. Update validation rules

### Modifying Test Structure
1. Adjust timing settings in the test creation form
2. Modify module progression logic in `SessionController`
3. Update view templates for new modules

### Styling Customization
- Modify Tailwind CSS classes in view templates
- Update color schemes in `tailwind.config.js`
- Customize component styling in `resources/css/`

## API Endpoints

### Admin Routes
- `GET /admin/tests` - List all tests
- `POST /admin/tests` - Create new test
- `PUT /admin/tests/{id}` - Update test
- `DELETE /admin/tests/{id}` - Delete test
- `POST /admin/tests/{id}/publish` - Publish test

### Student Routes
- `GET /student/register/{test}` - Registration form
- `POST /student/register/{test}` - Submit registration
- `GET /student/session/{token}` - Test dashboard
- `POST /student/session/{token}/answer` - Submit answer
- `POST /student/session/{token}/complete-module` - Complete module

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.0.0
- Initial release
- Complete IELTS test platform
- Security features and anti-cheating measures
- Admin and student interfaces
- Sample test data included
