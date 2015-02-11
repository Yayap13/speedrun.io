<?php
namespace Yap\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class AntiFloodValidator extends ConstraintValidator
{
	private $request;
	private $em;

	public function __construct(Request $request, EntityManager $em)
	{
		$this->request = $request;
		$this->em = $em;
	}

	public function validate($value, Constraint $constraint)
	{
		$ip = $this->request->server->get('REMOTE_ADDR');

		$isFlood = $this->em->getRepository('YapBlogBundle:Comments')
							->isFlood($ip, 15);
		if (strlen($value) < 3 && $isFlood) {
			$this->context->addViolation($constraint->message);
		}
	}
}