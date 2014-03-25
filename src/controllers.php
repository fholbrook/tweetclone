<?php
/**
 * This file contains all logic except some predfine database operations (dbClass.php) for the application.
 */

/**
 * Register a few components for use with forms and validations.
 */
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Route for homepage
 * 
 * @return twig with array forms and tweets for homepage.
 */
$app->match('/', function (Request $request) use ($app) {
    //define our loginform
    $form = $app['form.factory']->createBuilder('form')
        ->add('tweet', 'textarea', array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('max' => 140))),
            'attr' => array('class' => 'form-control', 'placeholder' => 'Post a Tweet', 'style' => 'margin-bottom: 15px;', 'rows' => 2),
            'label' => false,
            'error_bubbling' => true
        ))
        ->add('submit', 'submit', array(
            'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 15px;')
        ))
        ->getForm();
        
    $form->handleRequest($request);
    
    //Process login form submission
    if ($form->isValid()) {
        $data = $form->getData();
        try {
            $sql = "INSERT into tweets(message, user_id) VALUES(?,?)";
            $stmt = $app['db']->prepare($sql);
            $stmt->bindValue(1, $data['tweet']);
            $stmt->bindValue(2, 1);
            $stmt->execute();
        } catch (Exception $ex) {
            $form->get('tweet')->addError(new FormError('Tweet not posted, try again later!'));
        }
    }
    
    //get tweets
    $data = $app['db']->fetchAll("SELECT * from tweets ORDER BY id DESC");
    //change the date format
    foreach ($data as $key => $tweet) {
        $data[$key]['time_created'] = date('h:m m/d/Y', strtotime($tweet['time_created'])).' EST';
    }
    //return with twig data
    return $app['twig']->render('index.twig', array(
        'form' => $form->createView(),
        'tweets' => $data
    ));
});
