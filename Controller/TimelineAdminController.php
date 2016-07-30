<?php

namespace Rz\TimelineBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Route\RouteCollection;
use Spy\Timeline\Model\TimelineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TimelineAdminController extends CRUDController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function timelineAction(Request $request = null)
    {

        $token = $this->get('security.token_storage')->getToken();
        if (!$token) {
            throw $this->createNotFoundException('No User Token');
        }

        $subject = $this->get('spy_timeline.action_manager')->findOrCreateComponent($token->getUser(), $token->getUser()->getId());


        $entries = $this->get('spy_timeline.timeline_manager')->getTimeline($subject, array(
          'page'            => $request->query->get('page') ?: 1,
          'max_per_page'    => 25,
          'type'            => TimelineInterface::TYPE_TIMELINE,
          'context'         => 'SONATA_ADMIN',
          'filter'          => true,
          'group_by_action' => true,
          'paginate'        => true
        ));


        if ($request->isXmlHttpRequest()) {
            $content = $this->render('RzTimelineBundle:TimelineAdmin:timeline_ajax.html.twig', array(
                                     'entries'    => $entries,
                                     'admin_pool' => $this->get('sonata.admin.pool')));

            $loadMore = $this->render('RzTimelineBundle:TimelineAdmin:timeline_load_more.html.twig', array(
                                      'entries'    => $entries,
                                      'admin_pool' => $this->get('sonata.admin.pool')));

            return new JsonResponse(array('status' => 'OK', 'content' => $content->getContent(), 'loadMore'=>$loadMore->getContent()));
        } else {
            return $this->render('RzTimelineBundle:TimelineAdmin:timeline.html.twig', array(
                'action'     => 'timeline',
                'entries'    => $entries,
                'admin_pool' => $this->get('sonata.admin.pool')
            ));
        }
    }
}
