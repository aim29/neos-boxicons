<?php
namespace Aim29\Boxicons\Icon\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Symfony\Component\Yaml\Yaml;

class BoxiconsDataSource extends AbstractDataSource
{
    /**
     * @var string
     */
    static protected $identifier = 'aim29-boxicons';

    const STYLE_REGULAR = 'regular';
    const STYLE_SOLID = 'solid';
    const STYLE_LOGO = 'logos';

    protected $styleCode = [
        self::STYLE_REGULAR => 'bx',
        self::STYLE_SOLID => 'bxs',
        self::STYLE_LOGO => 'bxl'
    ];

    /**
     * @Flow\InjectConfiguration()
     */
    protected $configuration;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\ResourceManagement\ResourceManager
     */
    protected $resourceManager;

    public function getData(NodeInterface $node = null, array $arguments = [])
    {
        $editorOptionValues = [];
        $iconMetaData = $this->loadIconMetaData();

        foreach ($iconMetaData as $name => $data) {
            foreach ($data['styles'] as $style) {
                // Skip disabled Styles
                if(!isset($this->configuration['styles'][$style]) || !$this->configuration['styles'][$style]){
                    continue;
                }
                
                $styleCode = $this->styleCode[$style] ?? $this->styleCode[self::STYLE_REGULAR];
                $optionKey = $this->getIconCode($style, $name);
                $editorOptionValues[$optionKey] = [
                    'label' => $data['label'],
                    'group' => $style,
                    'preview' => $this->resourceManager->getPublicPackageResourceUri('Aim29.Boxicons.Icon', sprintf('boxicons/%s/%s-%s.svg', $style, $styleCode, $name))
                ];
            }

        }
        return $editorOptionValues;
    }

    protected function loadIconMetaData() : array
    {
        $fileName = 'resource://Aim29.Boxicons.Icon/Private/Metadata/icons.yml';
        return (array) Yaml::parseFile($fileName);
    }

    protected function getIconCode($style, $name)
    {
        $styleCode = $this->styleCode[$style] ?? $this->styleCode[self::STYLE_REGULAR];
        return sprintf('bx %s-%s', $styleCode, $name);
    }
}
