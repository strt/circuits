<?php

/* test.twig */
class __TwigTemplate_caf862190c240bc8f0c445ee7b6dba4f008060ab4535d44cd983d49fe68afdcc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <meta charset=\"utf-8\">
</head>
<body>
    <h1>Hejsan</h1>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "test.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "test.twig", "/home/vagrant/Code/betest/public/app/themes/boilerplate/resources/views/test.twig");
    }
}
