<?php

namespace App\Livewire\Admin;

use App\Models\LandingConfig as LandingConfigModel;
use Livewire\Component;

class LandingConfig extends Component
{
    public $configs = [];
    public $currentGroup = 'hero';

    public $groups = [
        'hero' => 'Hero Section',
        'pricing' => 'Precios',
        'testimonials' => 'Testimonios',
        'stats' => 'Estadísticas',
        'social' => 'Redes Sociales',
        'branding' => 'Marca (Color)',
    ];

    public function mount()
    {
        $this->loadConfigs();
    }

    public function loadConfigs()
    {
        $allConfigs = LandingConfigModel::where('group', $this->currentGroup)
            ->orderBy('id')
            ->get();

        $this->configs = [];
        foreach ($allConfigs as $config) {
            $value = $config->value;

            // Decode JSON for display
            if ($config->type === 'json' && is_string($value)) {
                $value = json_decode($value, true);
            }

            $this->configs[$config->key] = [
                'id' => $config->id,
                'value' => $value,
                'type' => $config->type,
                'label' => $config->label,
                'description' => $config->description,
            ];
        }
    }

    public function changeGroup($group)
    {
        $this->currentGroup = $group;
        $this->loadConfigs();
    }

    public function save()
    {
        try {
            foreach ($this->configs as $key => $data) {
                $value = $data['value'];

                // Encode arrays to JSON
                if ($data['type'] === 'json' && is_array($value)) {
                    $value = json_encode($value);
                }

                LandingConfigModel::where('id', $data['id'])->update([
                    'value' => $value,
                ]);
            }

            // Clear cache
            LandingConfigModel::clearCache();

            session()->flash('success', 'Configuración actualizada exitosamente');

            // Reload configs
            $this->loadConfigs();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function addFeature($configKey)
    {
        if (!isset($this->configs[$configKey])) {
            return;
        }

        if (!is_array($this->configs[$configKey]['value'])) {
            $this->configs[$configKey]['value'] = [];
        }

        $this->configs[$configKey]['value'][] = '';
    }

    public function removeFeature($configKey, $index)
    {
        if (isset($this->configs[$configKey]['value'][$index])) {
            unset($this->configs[$configKey]['value'][$index]);
            $this->configs[$configKey]['value'] = array_values($this->configs[$configKey]['value']);
        }
    }

    public function render()
    {
        return view('livewire.admin.landing-config')->layout('layouts.admin');
    }
}
