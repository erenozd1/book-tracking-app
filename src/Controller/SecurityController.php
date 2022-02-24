<?php
namespace App\Controller;
use App\Entity\AccessToken;
use App\Entity\Book;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserBundle;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\Response;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\RestBundle\Controller\AnnotationsasRest;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/api",name="api_")
 */
class SecurityController extends AbstractFOSRestController
{
    private $client_manager;
    private $tokenController;
    private $userManager;

    public function __construct(ClientManagerInterface $client_manager, TokenController $tokenController, UserManagerInterface $userManager,ORM\EntityManagerInterface $entityManager)
    {
        $this->client_manager = $client_manager;
        $this->tokenController = $tokenController;
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;

    }
    /**
     * Create Client and get an Access Token.
     * @FOSRest\Post("/login")
     *
     * @OA\RequestBody(
     *  @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *          required={"username","password"},
     *          @OA\Property(property="username", type="string", example="example@flowq.com"),
     *          @OA\Property(property="password", type="string", example="example")
     *      )
     *  )
     *)
     * @OA\Tag(name="Login")
     * @return Response
     */
    public function AuthenticationAction(Request $request)
    {
        $params = json_decode($request->getContent());

        //create client for send public_id  to login request
        $client = $this->client_manager->createClient();
        $client->setAllowedGrantTypes(array('password'));
        $this->client_manager->updateClient($client);

        $allowed_grant_type = $client->getAllowedGrantTypes()[0];

        $request->request->set('client_id', $client->getPublicId());
        $request->request->set('client_secret', $client->getSecret());
        $request->request->set('grant_type', $allowed_grant_type);
        $request->request->set('username', $params->{"username"});
        $request->request->set('password', $params->{"password"});
        $request->request->set('code', '123123');
        $request->request->set('redirect_uri', 'http://127.0.0.1:8000/api/book_with_contributor');

        return $this->tokenController->tokenAction($request);
    }

    /**
     * Register as  user
     * @FOSRest\Post("/register")
     * @param Request $request
     * @return bool|string

     * @OA\RequestBody(
     *  @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *          required={"email","username","password"},
     *          @OA\Property(property="email", type="string", example="example@flowq.com"),
     *          @OA\Property(property="username", type="string", example="example123"),
     *          @OA\Property(property="password", type="string", example="examplexxx"),
     *          @OA\Property(property="phone", type="string", example="0555XXXXXXX")
     *      )
     *  )
     *)
     * @OA\Tag(name="Register")
     */
    public function registeruserAction(Request $request){

        $request = json_decode($request->getContent());
        $response = ['status' => 'success', 'message'=>''];

        $succesfullyRegistered = $this->register($this->userManager , $request);
        if($succesfullyRegistered){
            $response['message'] = 'Kayıt işleminiz başarıyla tamamlandı';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Bu email zaten kayıtlı';
        }

        return $response;
    }

    /**
     * Update an existing user
     * @FOSRest\Put("/update")
     * @param Request $request
     * @return bool|string

     * @OA\RequestBody(
     *  @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *          required={"email","username","password"},
     *          @OA\Property(property="email", type="string", example="example@flowq.com"),
     *          @OA\Property(property="username", type="string", example="example123"),
     *          @OA\Property(property="password", type="string", example="examplexxx"),
     *          @OA\Property(property="phone", type="string", example="0555XXXXXXX")
     *      )
     *  )
     *)
     * @OA\Tag(name="User")
     */
    public function updateUserAction(Request $request){

        $request = json_decode($request->getContent(),true);
        $response = ['status' => 'success', 'message'=>''];
        $succesfullyRegistered = $this->updateUser($this->userManager , $request);
        if($succesfullyRegistered){
            $response['message'] = 'Bilgileriniz başarıyla güncellendi';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Bu email zaten kayıtlı';
        }

        return $response;
    }
    /**
     * Create Client and get an Access Token.
     * @FOSRest\Post("/logout")
     *
     * @OA\Tag(name="Login")
     * @return Response
     */
    public function LogoutAction(Request $request){

        $user = $this->getUser();
        $user_id = $user->getId();

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(AccessToken::class,'at');
        $rsm->addFieldResult('at','id','id');
        $query = $this->entityManager->createNativeQuery('SELECT * FROM  access_token WHERE user_id = :user_id', $rsm);
        $query->setParameter('user_id',$user_id);
        return $query->getResult();
    }

    /**
     * This method registers an user in the database manually.
     *
     * @return boolean User registered / not registered
     **/
    private function register($userManager, $request){
        $email_exist = $userManager->findUserByEmail($request->email);

        if($email_exist){
            return false;
        }
        $user = $userManager->createUser();
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $user->setEmailCanonical($request->email);
        $user->setEnabled(1);
        $user->setPlainPassword($request->password);
        $user->setPhone($request->phone);
        $userManager->updateUser($user);

        return true;
    }

    /**
     * This method update   an user in the database manually.
     *
     * @return boolean User registered / not registered
     **/
    private function updateUser($userManager, $request){
        $user = $this->getUser();

        $user->setUsername($request['username']);
        $user->setEmail($request['email']);
        $user->setEmailCanonical($request['email']);
        $user->setPlainPassword($request['password']);
        $user->setPhone($request['phone']);
        $userManager->updateUser($user);

        return true;
    }

}