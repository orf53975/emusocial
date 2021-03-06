<?php
//'route' => 'method;class;function',
$routes = 
[
    '/student/login' => 'post;loginStudent;runPost',

    '/user/saveToken' => 'post;loginStudent;saveToken',

    '/upload/{type}' => 'post;uploadApi;runPost',

    '/notification/new' => 'get;notificationApi;newFun',

    '/notification/all' => 'get;notificationApi;allFun',
    
    ///////////////////////////////////////////////////////////////////////////////

    '/createProfilePost' => 'post;postApi;createProfile',

    '/profileTimeline' => 'get;postApi;getProfilePosts',

    '/deletePost' => 'post;postApi;deletePost',

    ///////////////////////////////////////////////////////////////////////////////
    
    '/submitComment' => 'post;commentApi;createComment',

    '/deleteComment' => 'post;commentApi;deleteComment',

    '/getComments' => 'get;commentApi;getComments',

    ///////////////////////////////////////////////////////////////////////////////

    '/likePost' => 'post;likeApi;likePost',

    '/dislikePost' => 'post;likeApi;dislikePost',

    '/likeComment' => 'post;likeApi;likeComment',

    '/dislikeComment' => 'post;likeApi;dislikeComment',
    ///////////////////////////////////////////////////////////////////////////////
    
    '/chat/sendMessage' => 'post;chatApi;sendMessage',

    '/chat/getNewMessages' => 'get;chatApi;getNewMessages',

    '/chat/getMessages' => 'get;chatApi;getMessages',

    '/chat/getSummary' => 'get;chatApi;getSummary',

    ///////////////////////////////////////////////////////////////////////////////
    
    '/course/getStudents' => 'get;courseApi;getStudents',

    '/course/getTimeline' => 'get;courseApi;getTimeline',

    '/course/getAnnouncements' => 'get;courseApi;getAnnouncements',

    '/course/getEvents' => 'get;courseApi;getEvents',

    '/course/getGrades' => 'get;courseApi;getGrades',

    '/course/getAdminFiles' => 'get;courseApi;getAdminFiles',

    '/course/getAllFiles' => 'get;courseApi;getAllFiles',

    '/course/getBroadcasts' => 'get;courseApi;getBroadcasts',

    '/course/post' => 'post;courseApi;post',

    ///////////////////////////////////////////////////////////////////////////////

    '/home/userTimeline' => 'get;timelineApi;getUserTimeline',

];



////////////////////////////////////////////////////////////////////////////////
///
////////////////////////////////////////////////////////////////////////////////





















$app->get('/api/dummy', function ( $request,  $response,$arguments) {
    $arr = ['RC' => 200,'results' => ['id'=>1,'username'=>'elmahdy sharidy','student_number'=>'146932']];
    return $response->withJson($arr,200);

});


foreach ($routes as $route => $det) {
    $details = explode(';',$det);

    $route = "/api".$route.'[/]';
    $method = $details[0];
    $class_name = '\App\APIs\\'.$details[1];
    $function_name = $details[2];
    // var_dump($class_name);die;

    if ($method == 'get') {
        $app->get($route, function ($request, $response, $arguments) use ($class_name, $function_name) {
            $class = new $class_name($request,$response,$arguments);
            return $class->$function_name();
        });

    } else {
        $app->post($route, function ($request, $response, $arguments) use ($class_name,$function_name) {
            $class = new $class_name($request,$response,$arguments);
            return $class->$function_name();
        });
    }
    
}


