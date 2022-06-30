<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Entity denormalizer
 * Automatically deserialize doctrine entities when using Symfony Serializer
 */
class EntityDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface **/
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        // We will need the EntityManager to recover our entity in base
        $this->em = $em;
    }

    /**
     * Should this denormalizer be applied to the current data ?
     * if so, we call $this->denormalize()
     * 
     * $data => the id of Entity, if the JSON contains (e.g. "technologies": [2, 9])
     * => ids that are in your database
     * $type => the type of the class to which we want to denormalize $data
     * 
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        // Is the class of type doctrine Entity ?
        // Is the data provided numeric ?
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data));
    }

    /**
     * This method will be called if the condition above is valid
     * 
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // EntityManager->find() = shortcut to the Repository->find()
        return $this->em->find($class, $data);
    }
}