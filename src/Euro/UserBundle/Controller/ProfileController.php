<?php

namespace Euro\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends Controller {

	public function showAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		$em = $this->container->get('doctrine')->getEntityManager();

		$coin_values = array();
		$sorted = array();
		$translator = $this->container->get('translator');
		$user_coins = $em->getRepository('EuroCoinBundle:UserCoin')->getByUser($user);
		foreach ($user_coins as $uc) {
			$coin = $uc->getCoin();
			$country = $coin->getCountry();
			$value = $coin->getValue();

			$coin_values[$country->getId()][$value->getId()] = (string) $value;
			$sorted[$translator->trans($country)][] = $uc;
		}

		ksort($sorted);

		$coins = array();
		foreach ($sorted as $country) {
			$coins = array_merge($coins, $country);
		}

		foreach ($coin_values as $country => &$cv) {
			rsort($cv);
		}

		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.' . $this->container->getParameter('fos_user.template.engine'), array(
					'coins' => $coins,
					'coin_values' => $coin_values,
					'user' => $user,
				));
	}

	public function viewAction($id) {
		// Generate other member profil page
	}

}