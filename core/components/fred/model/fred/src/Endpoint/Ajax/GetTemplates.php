<?php
/*
 * This file is part of the Fred package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fred\Endpoint\Ajax;


class GetTemplates extends Endpoint
{
    protected $allowedMethod = ['OPTIONS', 'GET'];
    protected $templates = [];
    protected $map = [];
    protected $resources = [];

    /**
     * @return string
     */
    function process()
    {
        $c = $this->modx->newQuery('FredThemedTemplate');
        $c->leftJoin('modTemplate', 'Template');
        
        $c->select($this->modx->getSelectColumns('FredThemedTemplate', 'FredThemedTemplate'));
        $c->select($this->modx->getSelectColumns('modTemplate', 'Template', 'template_'));
        
        $c->sortby('template_templatename');
        
        /** @var \FredThemedTemplate[] $themes */
        $themes = $this->modx->getIterator('FredTheme', $c);
        $data = [];
        
        foreach ($themes as $theme) {
            $data[] = [
                'id' => $theme->template_id,
                'value' => (string)$theme->template_id,
                'name' => $theme->template_templatename,
                'customProperties' => [
                    'theme' => intval($theme->theme)
                ]
            ];    
        }

        return $this->data(['templates' => $data]);
    }
}
