<?php

namespace Rz\TimelineBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Model\TimelineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sonata\TimelineBundle\Block\TimelineBlock;

/**
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class TimelineBlockService extends TimelineBlock
{
    /**
     * @var SecurityContextInterface
     */
    protected $adminPool;

    /**
     * @return SecurityContextInterface
     */
    public function getAdminPool()
    {
        return $this->adminPool;
    }

    /**
     * @param SecurityContextInterface $adminPool
     */
    public function setAdminPool($adminPool)
    {
        $this->adminPool = $adminPool;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $token = $this->securityContext->getToken();

        if (!$token) {
            return new Response();
        }

        $subject = $this->actionManager->findOrCreateComponent($token->getUser(), $token->getUser()->getId());

        $entries = $this->timelineManager->getTimeline($subject, array(
            'page'            => 1,
            'max_per_page'    => $blockContext->getSetting('max_per_page'),
            'type'            => TimelineInterface::TYPE_TIMELINE,
            'context'         => $blockContext->getSetting('context'),
            'filter'          => $blockContext->getSetting('filter'),
            'group_by_action' => $blockContext->getSetting('group_by_action'),
            'paginate'        => $blockContext->getSetting('paginate'),
        ));

        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'context'  => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block'    => $blockContext->getBlock(),
            'entries'  => $entries,
            'admin_pool' => $this->adminPool
        ), $response);
    }
}
