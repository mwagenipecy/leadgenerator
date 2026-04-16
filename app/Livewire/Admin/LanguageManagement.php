<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class LanguageManagement extends Component
{
    public $languages = [];
    public $currentLanguage = 'en';
    public $translations = [];
    public $searchKey = '';
    public $selectedFile = 'common';
    public string $landingGroup = 'all'; // For landing.php: header/footer grouping
    public $newKey = '';
    public $newValue = '';
    public $editingKey = null;
    public $editingValue = '';

    private array $landingHeaderKeys = [
        // Landing navbar
        'eligibility',
        'process',
        'customer_help',
        'blog',
        'get_started',
    ];

    private array $landingFooterKeys = [
        // Landing footer
        'connecting_borrowers',
        'quick_links',
        'home',
        'eligibility',
        'how_it_works',
        'customer_help',
        'support',
        'help_center',
        'contact',
        'blog',
    ];

    public function mount()
    {
        $this->loadLanguages();
        $this->loadTranslations();
    }

    public function loadLanguages()
    {
        $langPath = resource_path('lang');
        $directories = File::directories($langPath);
        
        $this->languages = collect($directories)->map(function ($dir) {
            return basename($dir);
        })->toArray();
    }

    public function loadTranslations()
    {
        $filePath = resource_path("lang/{$this->currentLanguage}/{$this->selectedFile}.php");
        
        if (File::exists($filePath)) {
            $this->translations = require $filePath;
        } else {
            $this->translations = [];
        }
    }

    public function updatedCurrentLanguage()
    {
        $this->loadTranslations();
    }

    public function updatedSelectedFile()
    {
        // Reset section when switching files (only landing uses this grouping)
        $this->landingGroup = 'all';
        $this->loadTranslations();
    }

    public function getFilteredTranslations()
    {
        $translations = $this->translations;

        // Optional grouping for the landing page header/footer.
        if ($this->selectedFile === 'landing' && $this->landingGroup !== 'all') {
            $groupKeys = $this->landingGroup === 'header' ? $this->landingHeaderKeys : $this->landingFooterKeys;
            $translations = array_intersect_key($translations, array_flip($groupKeys));
        }

        if (empty($this->searchKey)) {
            return $translations;
        }

        return collect($translations)->filter(function ($value, $key) {
            return stripos($key, $this->searchKey) !== false ||
                   stripos($value, $this->searchKey) !== false;
        })->toArray();
    }

    public function startEdit($key)
    {
        $this->editingKey = $key;
        $this->editingValue = $this->translations[$key] ?? '';
    }

    public function cancelEdit()
    {
        $this->editingKey = null;
        $this->editingValue = '';
    }

    public function saveTranslation()
    {
        if (!$this->editingKey) {
            return;
        }

        $this->translations[$this->editingKey] = $this->editingValue;
        $this->saveTranslations();
        $this->cancelEdit();
        session()->flash('success', __('common.item_updated'));
    }

    public function addTranslation()
    {
        if (empty($this->newKey) || empty($this->newValue)) {
            session()->flash('error', __('common.required'));
            return;
        }

        $this->translations[$this->newKey] = $this->newValue;
        $this->saveTranslations();
        
        $this->newKey = '';
        $this->newValue = '';
        session()->flash('success', __('common.item_created'));
    }

    public function deleteTranslation($key)
    {
        unset($this->translations[$key]);
        $this->saveTranslations();
        session()->flash('success', __('common.item_deleted'));
    }

    private function saveTranslations()
    {
        $filePath = resource_path("lang/{$this->currentLanguage}/{$this->selectedFile}.php");
        $directory = dirname($filePath);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0775, true);
        }

        if (!is_writable($directory)) {
            throw ValidationException::withMessages([
                'translation' => 'Language file directory is not writable. Please contact support.',
            ]);
        }

        $content = "<?php\n\nreturn [\n";
        
        foreach ($this->translations as $key => $value) {
            $value = addslashes($value);
            $content .= "    '{$key}' => '{$value}',\n";
        }
        
        $content .= "];\n";
        
        if (File::put($filePath, $content) === false) {
            throw ValidationException::withMessages([
                'translation' => 'Unable to save translations at the moment. Please try again.',
            ]);
        }

        $this->loadTranslations();
    }

    public function getAvailableFiles()
    {
        $langPath = resource_path("lang/{$this->currentLanguage}");
        
        if (!File::exists($langPath)) {
            return [];
        }

        $files = File::files($langPath);
        
        return collect($files)->map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        })->toArray();
    }

    public function render()
    {
        $availableFiles = $this->getAvailableFiles();
        $filteredTranslations = $this->getFilteredTranslations();
        
        return view('livewire.admin.language-management', [
            'availableFiles' => $availableFiles,
            'filteredTranslations' => $filteredTranslations,
        ]);
    }
}

