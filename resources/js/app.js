import './bootstrap';

// Global utility functions for the IELTS test platform
window.IELTSApp = {
    // Anti-cheating utilities
    security: {
        // Prevent common keyboard shortcuts
        preventShortcuts: function() {
            document.addEventListener('keydown', function(e) {
                // Prevent Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+A, Ctrl+Z, Ctrl+Y, Ctrl+S, Ctrl+P, F5, Ctrl+R, Escape
                if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'z' || e.key === 'y' || e.key === 's' || e.key === 'p')) || 
                    e.key === 'F5' || 
                    (e.ctrlKey && e.key === 'r') ||
                    e.key === 'Escape') {
                    e.preventDefault();
                    return false;
                }
            });
        },

        // Prevent right-click context menu
        preventContextMenu: function() {
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });
        },

        // Prevent text selection
        preventTextSelection: function() {
            document.addEventListener('selectstart', function(e) {
                e.preventDefault();
                return false;
            });
        },

        // Prevent drag and drop
        preventDragDrop: function() {
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
        },

        // Monitor fullscreen changes
        monitorFullscreen: function() {
            document.addEventListener('fullscreenchange', function() {
                if (!document.fullscreenElement) {
                    IELTSApp.security.recordCheatAttempt('Exited fullscreen mode');
                }
            });
        },

        // Monitor visibility changes
        monitorVisibility: function() {
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    IELTSApp.security.recordCheatAttempt('Page hidden/tab switched');
                }
            });
        },

        // Monitor window focus
        monitorFocus: function() {
            window.addEventListener('blur', function() {
                IELTSApp.security.recordCheatAttempt('Window lost focus');
            });
        },

        // Record cheat attempts
        recordCheatAttempt: function(attempt) {
            console.warn('Security violation detected:', attempt);
            // This would typically send data to the server
            if (window.currentSessionToken) {
                fetch(`/student/session/${window.currentSessionToken}/heartbeat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        fullscreen: !!document.fullscreenElement,
                        focused: !document.hidden,
                        right_click: attempt.includes('right-click'),
                        keyboard_shortcut: attempt.includes('keyboard'),
                        tab_switch: attempt.includes('tab')
                    })
                });
            }
        },

        // Initialize all security measures
        init: function() {
            this.preventShortcuts();
            this.preventContextMenu();
            this.preventTextSelection();
            this.preventDragDrop();
            this.monitorFullscreen();
            this.monitorVisibility();
            this.monitorFocus();
        }
    },

    // Timer utilities
    timer: {
        // Create a countdown timer
        createCountdown: function(duration, elementId, onComplete) {
            let timeLeft = duration;
            const element = document.getElementById(elementId);
            
            const timer = setInterval(function() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                if (element) {
                    element.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
                
                timeLeft--;
                
                if (timeLeft < 0) {
                    clearInterval(timer);
                    if (onComplete) onComplete();
                }
            }, 1000);
            
            return timer;
        },

        // Format time in seconds to MM:SS
        formatTime: function(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
    },

    // UI utilities
    ui: {
        // Show notification
        showNotification: function(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        // Confirm dialog
        confirm: function(message, onConfirm, onCancel) {
            if (confirm(message)) {
                if (onConfirm) onConfirm();
            } else {
                if (onCancel) onCancel();
            }
        },

        // Loading spinner
        showLoading: function() {
            const spinner = document.createElement('div');
            spinner.id = 'loading-spinner';
            spinner.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            spinner.innerHTML = `
                <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white"></div>
            `;
            document.body.appendChild(spinner);
        },

        hideLoading: function() {
            const spinner = document.getElementById('loading-spinner');
            if (spinner) {
                spinner.remove();
            }
        }
    },

    // Form utilities
    form: {
        // Auto-save form data
        autoSave: function(formSelector, saveUrl, interval = 30000) {
            const form = document.querySelector(formSelector);
            if (!form) return;

            let timer = null;
            
            form.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    IELTSApp.form.saveForm(form, saveUrl);
                }, 2000);
            });
        },

        // Save form data
        saveForm: function(form, saveUrl) {
            const formData = new FormData(form);
            
            fetch(saveUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    IELTSApp.ui.showNotification('Progress saved', 'success');
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                IELTSApp.ui.showNotification('Failed to save progress', 'error');
            });
        },

        // Validate form
        validate: function(formSelector) {
            const form = document.querySelector(formSelector);
            if (!form) return false;

            const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            return isValid;
        }
    },

    // Audio utilities
    audio: {
        // Create secure audio player
        createSecurePlayer: function(audioUrl, controls = true) {
            const audio = document.createElement('audio');
            audio.src = audioUrl;
            audio.controls = controls;
            audio.preload = 'auto';
            
            // Disable audio controls
            audio.addEventListener('contextmenu', e => e.preventDefault());
            audio.addEventListener('selectstart', e => e.preventDefault());
            
            return audio;
        },

        // Prevent audio download
        preventDownload: function(audioElement) {
            audioElement.addEventListener('loadstart', function() {
                // Disable right-click on audio
                this.addEventListener('contextmenu', e => e.preventDefault());
            });
        }
    },

    // Word count utility
    wordCount: {
        // Count words in text
        count: function(text) {
            return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
        },

        // Update word count display
        update: function(textareaSelector, countSelector) {
            const textarea = document.querySelector(textareaSelector);
            const countElement = document.querySelector(countSelector);
            
            if (textarea && countElement) {
                textarea.addEventListener('input', function() {
                    const count = IELTSApp.wordCount.count(this.value);
                    countElement.textContent = count;
                });
                
                // Initial count
                const count = IELTSApp.wordCount.count(textarea.value);
                countElement.textContent = count;
            }
        }
    },

    // Highlighting utility for reading passages
    highlighting: {
        // Enable text highlighting
        enable: function(containerSelector) {
            const container = document.querySelector(containerSelector);
            if (!container) return;

            container.addEventListener('mouseup', function() {
                const selection = window.getSelection();
                if (selection.toString().length > 0) {
                    const range = selection.getRangeAt(0);
                    const highlightSpan = document.createElement('span');
                    highlightSpan.className = 'highlight bg-yellow-200 px-1 rounded';
                    highlightSpan.textContent = selection.toString();
                    
                    range.deleteContents();
                    range.insertNode(highlightSpan);
                    
                    selection.removeAllRanges();
                }
            });

            // Double-click to remove highlights
            container.addEventListener('dblclick', function(e) {
                if (e.target.classList.contains('highlight')) {
                    const text = e.target.textContent;
                    const textNode = document.createTextNode(text);
                    e.target.parentNode.replaceChild(textNode, e.target);
                }
            });
        }
    },

    // Initialize the application
    init: function() {
        // Initialize security measures
        this.security.init();
        
        // Set up global error handling
        window.addEventListener('error', function(e) {
            console.error('Application error:', e.error);
        });
        
        // Set up unhandled promise rejection handling
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Unhandled promise rejection:', e.reason);
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    IELTSApp.init();
});

// Export for use in other modules
export default IELTSApp;
