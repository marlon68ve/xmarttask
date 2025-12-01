<?php

class AccessControl {
    private $policy;
    private $rules = [];
    private $userType;

    public function __construct($policy = 'pallow') {
        $this->policy = $policy;
    }

    public function loadRules(array $rules) {
        foreach ($rules as $rule => $value) {
            list($action, $path) = explode(' ', $rule, 2);
            $roles = array_map('trim', explode(',', $value));
            $this->rules[] = [
                'action' => strtoupper($action),
                'path' => $path,
                'roles' => $roles
            ];
        }
    }

    public function authorize($userType, $currentPath) {
        $this->userType = $userType;

        foreach ($this->rules as $rule) {
            if (fnmatch($rule['path'], $currentPath)) {
                if ($rule['action'] === 'DENY' && in_array($userType, $rule['roles'])) {
                    return false; // Denied explicitly
                } elseif ($rule['action'] === 'ALLOW' && in_array($userType, $rule['roles'])) {
                    return true; // Allowed explicitly
                }
            }
        }

        // Apply default policy
        return $this->policy === 'pallow';
    }
}