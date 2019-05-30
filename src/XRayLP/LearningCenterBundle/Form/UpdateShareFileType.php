<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Request\UpdateShareFileRequest;

class UpdateShareFileType extends ContaoAbstractType
{

    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', HiddenType::class, array(
                'data_class' => null,
            ))
            ->add('submit', SubmitType::class)
        ;

        $builder->get('file')->addModelTransformer(new CallbackTransformer(
            function ($arrToString = null)
            {
                $str = '';
                $index = 0;

                if (isset($arrToString)) {
                    foreach ($arrToString as $file) {
                        if ($file instanceof File) {
                            if ($index == 0) {
                                $str += $file->getId();
                            } else {
                                $str += ',' . $file->getId();
                            }
                            $index++;
                        }
                    }
                }
                return $str;
            },
            function ($strToArray) {
                $files = $this->doctrine->getRepository(File::class)->findBy(['id' => explode(",", $strToArray)]);
                return $files;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UpdateShareFileRequest::class,
            'attr' => ['id' => 'form_update_share_file']
        ));
        parent::configureOptions($resolver);
    }
}