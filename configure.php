<?php

$packageSetup = new PackageSetup();
echo "Enter the package name:";
$package_name = trim(fgets(STDIN));
$packageSetup->setPackageName($package_name);
$packageSetup->replacePackageName('./README.md');


class PackageSetup
{
    private string $packageName;

    public function setPackageName(string $name): void
    {
        $this->packageName = $this->stringToSlug($name);
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function replacePackageName($file): void
    {
        $this->replacePlaceholder($file, '{your-package}', $this->getPackageName());
    }

    public function replacePlaceholder($file, $placeholder, $value): void
    {
        $content = file_get_contents($file);
        $content = str_replace($placeholder, $value, $content);
        file_put_contents($file, $content);
    }

    public function stringToSlug($string): string
    {
        $slug = str_replace(' ', '-', $string);
        return strtolower($slug);
    }
}
