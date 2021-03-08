<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class CreateTemplate extends Component
{
    /**
     * Skeleton layout to build.
     *
     * @var array
     */
    public array $skeleton;

    /**
     * Template content.
     *
     * @var string
     */
    public $template;

    /**
     * Type of template to build.
     *
     * @var string
     */
    public $type;

    /**
     * Template name.
     *
     * @var string
     */
    public $name;

    /**
     * Template description.
     *
     * @var string
     */
    public $description;

    /**
     * Validation rules.
     *
     * @var string[]
     */
    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];

    /**
     * Component mount instance.
     *
     * @param array $skeleton
     * @return void
     */
    public function mount(array $skeleton)
    {
        $this->skeleton = $skeleton;
        $this->template = $skeleton['template'];
        $this->type = $skeleton['type'];
    }

    /**
     * Create a new template for emailing.
     *
     * @return void
     */
    public function store()
    {
        // $this->validate();

        if (! preg_match("/^[a-zA-Z0-9-_\s]+$/", $this->name)) {
            $this->addError(
                'name',
                __('Template name not valid')
            );
            return;
        }

        $view = 'shopper::mails.templates.'.$this->name;
        $templateName = Str::camel(preg_replace('/\s+/', '_', $this->name));

        if (! view()->exists($view) && ! Mailable::getTemplates()->contains('template_slug', '=', $templateName)) {
            Mailable::saveTemplates(Mailable::getTemplates()
                ->push([
                    'template_name' => $this->name,
                    'template_slug' => $templateName,
                    'template_description' => $this->description,
                    'template_type' => $this->type,
                    'template_view_name' => $this->skeleton['name'],
                    'template_skeleton' => $this->skeleton['skeleton'],
                ]));

            $dir = resource_path('views/vendor/shopper/mails/templates');

            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            file_put_contents($dir."/{$templateName}.blade.php", Mailable::templateComponentReplace($this->template));

            session()->flash('success', __('Template successfully created!'));

            $this->redirectRoute('shopper::settings.mails');
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.templates.create');
    }
}
