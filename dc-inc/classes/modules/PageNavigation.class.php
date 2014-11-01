<?php

class PageNavigation extends ModuleModel
{

    protected function render()
    {
        if (!isset($_GET['show'])) {
            return 'Reload required';
        }
        $te = new TemplateEngine('site/modules/pagenavigation_show');
        $sm = new SiteController('*', 'filter:visibility');

        $this_id = $sm->filter_id($_GET['show']);
        if (isset($sm->sites[$this_id])) {
            $sites = $sm->get_subsites_from($sm->sites[$this_id]->subfrom);

            for ($i = 0; $i < sizeof($sites); $i++) {
                if ($sites[$i]->id == $this_id) {
                    $case['disabled_last'] = array_key_exists($i - 1, $sites) ? '' : 'disabled';
                    $case['disabled_next'] = array_key_exists($i + 1, $sites) ? '' : 'disabled';
                    $case['id_last'] = array_key_exists($i - 1, $sites) ? $sites[$i - 1]->get_url() : $sites[$i]->get_url() . '#';
                    $case['id_next'] = array_key_exists($i + 1, $sites) ? $sites[$i + 1]->get_url() : $sites[$i]->get_url() . '#';
                    break;
                }
            }
            $te->add_vars($case);
            return $te->render();
        } else {
            return 'Set this site public to activate this module (<a href="/dc-acp/admin?acp=acp_site&do=swap_visibility&id='.$this_id.'">instand toggle</a>)';
        }
    }

    protected function render_admin()
    {
        return '';
    }
}