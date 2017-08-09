<?php

namespace LearnosityQti\Services;

use LearnosityQti\Converter;
use LearnosityQti\Domain\JobDataTrait;
use LearnosityQti\Utils\AssumptionHandler;
use LearnosityQti\Utils\CheckValidQti;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ConvertToLearnosityService
{
    use JobDataTrait;

    const RESOURCE_TYPE_ITEM = 'imsqti_item_xmlv2p1';

    protected $inputPath;
    protected $outputPath;
    protected $output;

    /* Runtime options */
    protected $dryRun                     = false;
    protected $shouldAppendLogs           = false;
    protected $shouldGuessItemScoringType = true;
    protected $shouldUseManifest          = true;

    /* Job-specific configurations */
    // Overrides identifiers to be the same as the filename
    protected $useFileNameAsIdentifier    = false;
    // Uses the identifier found in learning object metadata if available
    protected $useMetadataIdentifier      = true;
    // Resource identifiers sometimes (but not always) match the assessmentItem identifier, so this can be useful
    protected $useResourceIdentifier      = false;

    public function __construct($inputPath, $outputPath, OutputInterface $output)
    {
        $this->inputPath = $inputPath;
        $this->outputPath = $outputPath;
        $this->output = $output;
    }

    public function process()
    {
        $errors = $this->validate();
        $result = [
            'status' => null,
            'message' => []
        ];

        if (count($errors)) {
            $result['status'] = 'false';
            $result['message'] = $errors;
            return $result;
        }

        $result = $this->parseContentPackage();

        return $result;
    }

    /**
     * Performs a conversion on each directory (one level deep)
     * inside the given source directory.
     */
    private function parseContentPackage()
    {
        $finalManifest = $this->getJobManifestTemplate();

        $finder = new Finder();
        foreach ($finder->directories()->in($this->inputPath)->depth(0) as $dir) {
            $dirName = $dir->getRelativePathname();

            $results = $this->convertQtiContentPackagesInDirectory($dir->getPathname(), $dirName);

            if (!isset($results['qtiitems'])) {
                continue;
            }

            $this->updateJobManifest($finalManifest, $results);
            $this->persistResultsFile($results, $targetDirectory . '/' . $dirName);
        }
        $this->flushJobManifest($finalManifest);




        // ✘ ✔
        // $finder = new Finder();
        // $manifest = $finder->files()->in($this->inputPath)->name('imsmanifest.xml');

        // foreach ($manifest as $file) {
        //     /** @var SplFileInfo $file */
        //     $currentDir   = realpath($file->getPath());
        //     $fullFilePath = realpath($file->getPathname());

        //     $this->output->writeln("<info>Parsing manifest file\t✔</info>");

        //     // build the DOMDocument object
        //     $manifestDoc = new \DOMDocument();
        //     $manifestDoc->load($fullFilePath);

        //     $itemResources = $this->getItemResourcesByHrefFromDocument($manifestDoc);

        //     $itemFinder = (new Finder())->in($currentDir)->name('*.xml')->notName('imsmanifest.xml');
        //     $itemFinder = $this->applyFilteringToFinder($itemFinder);
        //     $itemFinder = $itemFinder->filter(function (SplFileInfo $file) use ($itemResources) {
        //         return isset($itemResources[$file->getRelativePathname()]);
        //     });
        //     $itemFinder = $itemFinder->filter(function (SplFileInfo $file) {
        //         return !$this->containsPath($file, [
        //             '======= PUT BLACKLIST ITEMS HERE =======',
        //             'ITEM-LOGIC-CTPT-190716', // 'Parcc'
        //         ]);
        //     });

        //     $itemCount = 0;
        //     foreach ($itemFinder as $itemFile) {
        //         /** @var SplFileInfo $itemFile */
        //         $itemCount++;
        //         $totalItemCount++;
        //         $resourceHref    = $itemFile->getRelativePathname();
        //         $relatedResource = $itemResources[$resourceHref];
        //         $itemReference   = $this->getItemReferenceFromResource(
        //             $relatedResource,
        //             $this->useMetadataIdentifier,
        //             $this->useResourceIdentifier,
        //             $this->useFileNameAsIdentifier
        //         );

        //         $metadata = [];
        //         $itemPointValue = $this->getPointValueFromResource($relatedResource);
        //         if (isset($itemPointValue)) {
        //             $metadata['point_value'] = $itemPointValue;
        //         }

        //         $this->output->writeln("<comment>Converting item ({$totalItemCount}:{$itemCount}) reference [{$itemReference}]: $relativeDir/$resourceHref</comment>");
        //         $convertedContent = $this->convertAssessmentItemInFile($itemFile, $itemReference, $metadata);
        //         if (!empty($convertedContent)) {
        //             $results['qtiitems'][basename($relativeDir).'/'.$resourceHref] = $convertedContent;
        //         }
        //     }
        // }
    }

    /**
     * Performs a conversion on QTI content packages found in the given root source directory.
     *
     * @param  string $sourceDirectory
     * @param  string $relativeSourceDirectoryPath
     *
     * @return array - the results of the conversion
     */
    private function convertQtiContentPackagesInDirectory($sourceDirectory, $relativeSourceDirectoryPath)
    {
        $results = [];

        $manifestFinder = new Finder();
        $manifestFinderPath = $manifestFinder->files()->in($sourceDirectory)->name('imsmanifest.xml');

        $totalItemCount = 0;
        foreach ($manifestFinderPath as $manifestFile) {
            /** @var SplFileInfo $manifestFile */
            $currentDir   = realpath($manifestFile->getPath());
            $fullFilePath = realpath($manifestFile->getPathname());
            $relativeDir  = rtrim($relativeSourceDirectoryPath.'/'.$manifestFile->getRelativePath(), '/');
            $relativePath = rtrim($relativeSourceDirectoryPath.'/'.$manifestFile->getRelativePathname(), '/');

            $this->output->writeln("<info>Processing manifest file: {$relativePath} </info>");

            // build the DOMDocument object
            $manifestDoc = new \DOMDocument();
            $manifestDoc->load($fullFilePath);

            $itemResources = $this->getItemResourcesByHrefFromDocument($manifestDoc);

            $itemCount = 0;
            foreach ($itemResources as $resource) {
                $itemCount++;
                $totalItemCount++;
                $resourceHref    = $resource['href'];
                $relatedResource = $resource['resource'];
                $assessmentItemContents = file_get_contents($currentDir . '/' . $resourceHref);
                $itemReference   = $this->getItemReferenceFromResource(
                    $relatedResource,
                    $this->useMetadataIdentifier,
                    $this->useResourceIdentifier,
                    $this->useFileNameAsIdentifier
                );

                $metadata = [];
                $itemPointValue = $this->getPointValueFromResource($relatedResource);
                if (isset($itemPointValue)) {
                    $metadata['point_value'] = $itemPointValue;
                }

                $this->output->writeln("<comment>Converting item ({$totalItemCount}:{$itemCount}) reference [{$itemReference}]: $relativeDir/$resourceHref</comment>");
                $convertedContent = $this->convertAssessmentItemInFile($assessmentItemContents, $itemReference, $metadata, $currentDir, $resourceHref);
                if (!empty($convertedContent)) {
                    $results['qtiitems'][basename($relativeDir).'/'.$resourceHref] = $convertedContent;
                }
            }
        }

        return $results;
    }

    /**
     * Retrieves any <assessmentItem> resource elements found in a given
     * XML document.
     *
     * @param  DOMDocument $manifestDoc - the document to search
     *
     * @return array
     */
    private function getItemResourcesByHrefFromDocument(\DOMDocument $manifestDoc)
    {
        $itemResources = [];
        $resources = $manifestDoc->getElementsByTagName('resource');

        while (($resource = $resources->item(0)) != null) {
            $resourceHref = $resource->getAttribute('href');
            $resourceType = $resource->getAttribute('type');

            if ($resourceType === static::RESOURCE_TYPE_ITEM) {
                $itemResources[] = [
                    'href' => $resourceHref,
                    'resource' => $resource
                ];
            }

            // Remove the processed resource from the list for :toast:y performance reasons
            // see http://stackoverflow.com/a/13931470 regarding linear read performance
            $resource->parentNode->removeChild($resource);
        }

        return $itemResources;
    }

    /**
     * Checks whether a general identifier exists in the Learning Object Metadata
     * for this resource.
     *
     * @param  DOMNode $resource
     *
     * @return boolean
     */
    private function metadataIdentifierExists(\DOMNode $resource)
    {
        $xpath = new \DOMXPath($resource->ownerDocument);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');

        $searchResult = $xpath->query('.//qti:metadata/lom:lom/lom:general/lom:identifier', $resource);

        return $searchResult->length > 0;
    }

    /**
     * Converts a single <assessmentItem> file.
     *
     * @param  SplFileInfo $file
     * @param  string      $itemReference - Optional
     *
     * @return array - the results of the conversion
     */
    private function convertAssessmentItemInFile($contents, $itemReference = null, array $metadata = [], $currentDir, $resourceHref)
    {
        $results = null;

        try {
            $xmlString = $contents;
            // Check that we're on an <assessmentItem>
            if (!CheckValidQti::isAssessmentItem($xmlString)) {
                $this->output->writeln("<info>Not an <assessmentItem>, moving on...</info>");
                return $results;
            }

            $results = $this->convertAssessmentItem($xmlString, $itemReference, $currentDir, $metadata);
        } catch (\Exception $e) {
            $targetFilename = $resourceHref;
            $message        = $e->getMessage();
            $results        = [ 'exception' => $targetFilename . '-' . $message ];
            if (!StringHelper::contains($message, 'This is intro or outro')) {
                $this->output->writeln('<error>EXCEPTION with item ' . str_replace($this->directory, '', $file->getPathname()) . ' : ' . $message . '</error>');
            }
        }

        return $results;
    }

    /**
     * Converts a single <assessmentItem> XML string.
     *
     * @param  string $xmlString
     * @param  string $itemReference - Optional
     * @param  string $resourcePath  - Optional
     *
     * @return array - the results of the conversion
     *
     * @throws \Exception - if the conversion fails
     */
    private function convertAssessmentItem($xmlString, $itemReference = null, $resourcePath = null, array $metadata = [])
    {
        AssumptionHandler::flush();

        $xmlString = CheckValidQti::preProcessing($xmlString);

        $result     = Converter::convertQtiItemToLearnosity($xmlString, null, null, $resourcePath, $itemReference, $metadata);
        $item       = $result['item'];
        $questions  = $result['questions'];
        $features   = $result['features'];
        $manifest   = $result['messages'];
        $rubricItem = !empty($result['rubric']) ? $result['rubric'] : null;

        $questions = !empty($questions) ? $this->assetsFixer->fix($questions) : $questions;
        $features = !empty($features) ? $this->assetsFixer->fix($features) : $features;

        // Return those results!
        list($item, $questions) = CheckValidQti::postProcessing($item, $questions, $itemTags);

        if ($this->shouldGuessItemScoringType) {
            list($assumedItemScoringType, $scoringTypeManifest) = $this->getItemScoringTypeFromResponseProcessing($xmlString);
            if (isset($assumedItemScoringType)) {
                $item['metadata']['scoring_type'] = $assumedItemScoringType;
            }
            $manifest = array_merge($manifest, $scoringTypeManifest);
        }

        return [
            'item'        => $item,
            'questions'   => $questions,
            'features'    => $features,
            'manifest'    => $manifest,
            'rubric'      => $rubricItem,
            'assumptions' => AssumptionHandler::flush()
        ];
    }

    /**
     * Gets an item reference (if available) from a given resource.
     *
     * What to use as the item reference depends on the flags passed.
     * The order used for selecting an item reference, in ascending order
     * of priority, is as follows:
     *
     * - no item reference selected (if none found, or all options disabled)
     * - metadata identifier
     * - resource identifier attribute (if enabled)
     * - file name (if enabled)
     *
     * As such, if {$useFileNameAsIdentifier} is enabled, it takes precedence
     * over any other flags set before it.
     *
     * @param  DOMNode $resource
     * @param  boolean $useMetadataIdentifier   - Optional. true by default; set false to disable
     * @param  boolean $useResourceIdentifier   - Optional
     * @param  boolean $useFileNameAsIdentifier - Optional
     *
     * @return string|null
     */
    private function getItemReferenceFromResource(
        \DOMNode $resource,
        $useMetadataIdentifier = true,
        $useResourceIdentifier = false,
        $useFileNameAsIdentifier = false
    ) {
        $itemReference = null;

        if ($useMetadataIdentifier && $this->metadataIdentifierExists($resource)) {
            $itemReference = $this->getIdentifierFromResourceMetadata($resource);
        }

        if ($useResourceIdentifier) {
            $itemReference = $this->getAttribute('identifier');
        }

        if ($useFileNameAsIdentifier) {
            // This flag should override anything else that is set above
            $resourceHref  = $resource->getAttribute('href');
            $itemReference = $this->getIdentifierFromResourceHref($resourceHref);
        }

        return $itemReference;
    }

    private function getPointValueFromResource(\DOMNode $resource)
    {
        $pointValue = null;

        $xpath = $this->getXPathForQtiDocument($resource->ownerDocument);
        $pointValueEntries = ($xpath->query('./qti:metadata/lom:lom/lom:classification/lom:taxonPath/lom:source/lom:string[text() = \'cf$Point Value\']/../../lom:taxon/lom:entry', $resource));
        if ($pointValueEntries->length > 0) {
            $pointValue = (int)$pointValueEntries->item(0)->nodeValue;
        }

        return $pointValue;
    }

    private function getXPathForQtiDocument(\DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');

        return $xpath;
    }

    /**
     * Returns a finder that is based on the finder provided, with any
     * specified filtering rules applied.
     *
     * @param  Finder $finder
     * @param  array  $filtering
     *
     * @return Finder
     */
    private function applyFilteringToFinder(Finder $finder, $filtering = null)
    {
        if (!isset($filtering)) {
            $filtering = $this->filtering;
        }

        // If filtering set, then only process those. This is useful for debugging :)
        if (!empty($filtering)) {
            $finder = $finder->filter(function (SplFileInfo $file) use ($filtering) {
                return $this->containsPath($file, $filtering);
            });
        }

        return $finder;
    }

    /**
     * Flush and write the given job manifest.
     *
     * @param array $manifest
     */
    private function flushJobManifest(array $manifest)
    {
        if ($this->dryRun) {
            return;
        }
        $this->output->writeln('<info>Writing job manifest to file...</info>');
        $manifest['info']['question_types'] = array_values(array_unique($manifest['info']['question_types']));
        $manifest['imported_rubrics'] = array_values(array_unique($manifest['imported_rubrics']));
        $manifest['imported_items'] = array_values(array_unique($manifest['imported_items']));
        $manifest['ignored_items'] = array_values(array_unique($manifest['ignored_items']));

        $manifest['info']['rubric_count'] = count($manifest['imported_rubrics']);
        $manifest['info']['item_count'] = count($manifest['imported_items']);
        $manifest['info']['item_scoring_types_counts']['none'] = $manifest['info']['item_count'] - array_sum($manifest['info']['item_scoring_types_counts']);

        if ($this->shouldAppendLogs) {
            $manifestFileBasename = 'logs_'.date('m-d-y-His');
        } else {
            $manifestFileBasename = 'logs';
        }
        $this->writeJsonToFile($manifest, $manifestFileBasename.'.json');
    }

    /**
     * Returns the base template for job manifests consumed by this job.
     *
     * @return array
     */
    private function getJobManifestTemplate()
    {
        return [
            'info' => [
                'question_types' => [],
                'item_scoring_types_counts' => [],
            ],
            'imported_rubrics' => [],
            'imported_items' => [],
            'ignored_items' => [],
        ];
    }

    /**
     * Writes a given results file to the specified output path.
     *
     * @param array  $results
     * @param string $outputFilePath
     */
    private function persistResultsFile(array $results, $outputFilePath)
    {
        if ($this->dryRun) {
            return;
        }
        $this->output->writeln('<info>Writing job results to file...</info>');
        $innerPath = explode('/', $outputFilePath);
        array_pop($innerPath);
        FileSystemHelper::createDirIfNotExists(implode('/', $innerPath));
        file_put_contents($outputFilePath . '.json', json_encode($results));
    }

    /**
     * Updates a given job manifest in place with the contents of a specified
     * job partial result object.
     *
     * @param array $manifest - the job manifest to update
     * @param array $results  - the partial job result object to read
     */
    private function updateJobManifest(array &$manifest, array $results)
    {
        foreach ($results['qtiitems'] as &$itemResult) {
            // Log ignored items
            if (!isset($itemResult['item'])) {
                $manifest['ignored_items'][] = $itemResult['exception'];
                continue;
            }
            // Log processed items
            $itemReference = $itemResult['item']['reference'];
            $manifest['imported_items'][] = $itemReference;

            if (!empty($itemResult['rubric'])) {
                $rubricReference = $itemResult['rubric']['reference'];
                $manifest['imported_rubrics'][] = $rubricReference;
            }

            // Log item scoring type
            if (isset($itemResult['item']['metadata']['scoring_type'])) {
                ++$manifest['info']['item_scoring_types_counts'][$itemResult['item']['metadata']['scoring_type']];
            }
            foreach ($itemResult['questions'] as &$question) {
                // Log question types
                $manifest['info']['question_types'][] = $question['type'];
            }
            // Store assumptions
            if (!empty($itemResult['assumptions'])) {
                $manifest['warnings'][$itemReference] = array_unique($itemResult['assumptions']);
            }
        }
    }

    private function tearDown()
    {
        if (!$this->doConversion) {
            $this->output->writeln('<comment>No conversion run</comment>');
        }
        if (!$this->copySourceImages) {
            $this->output->writeln('<comment>No assets copied</comment>');
        }
        if (!$this->doImport) {
            $this->output->writeln('<comment>No import run, are you happy with the JSON? If so set, `$this->doImport = true`</comment>');
        }
    }

    private function validate()
    {
        $errors = [];
        $finder = new Finder();
        $manifest = $finder->files()->in($this->inputPath)->name('imsmanifest.xml');

        if ($manifest->count()) {
            foreach ($manifest as $file) {
                if (empty($file->getRealPath())) {
                }
            }
        } else {
            array_push($errors, 'Manifest not found');
        }

        return $errors;
    }
}
