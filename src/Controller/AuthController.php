<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\AuthService;
use App\ControleFinanceiro\Http\RequestHandler;

class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        if (AuthSession::has()) {
            header('Location: /dashboard');
            exit;
        }
        $this->render('index/login');
    }

    public function showRegister(): void
    {
        if (AuthSession::has()) {
            header('Location: /dashboard');
            exit;
        }
        $this->render('index/register');
    }

    public function authenticate(): void
    {
        if (!$this->request->isPost()) {
            $this->respondMethodNotAllowed();
            return;
        }

        $email = $this->extractEmail();
        $password = $this->extractPassword();

        $errors = $this->authService->validateLoginCredentials($email, $password);
        if (!empty($errors)) {
            $this->respondValidationError($errors);
            return;
        }

        $user = $this->authService->authenticate($email, $password);
        if (!$user) {
            $this->respondAuthenticationFailed('Email ou senha incorretos');
            return;
        }

        AuthSession::set($user);
        $this->respondSuccess(['redirect' => '/dashboard']);
    }

    public function register(): void
    {
        try {
            if (!$this->request->isPost()) {
                $this->respondMethodNotAllowed();
                return;
            }

            $name = $this->extractName();
            $email = $this->extractEmail();
            $password = $this->extractPassword();

            $errors = $this->authService->validateRegistrationData($name, $email, $password);
            if (!empty($errors)) {
                $this->respondValidationError($errors);
                return;
            }

            $userId = $this->authService->createUser($name, $email, $password);

            // Agora o AuthSession vai guardar o ID na chave 'auth_user'
            AuthSession::set($userId);

            // Garante que NADA foi impresso antes daqui
            $this->respondSuccess(['message' => 'Conta criada com sucesso']);

        } catch (\Exception $e) {
            // Se der erro, responde um JSON de erro, não um erro fatal do PHP
            $this->respondError($e->getMessage(), 500);
        }
    }

    public function logout(): void
    {
        AuthSession::clear();
        header('Location: /login');
        exit;
    }

    public function dashboard(): void
    {
        $this->requireAuth();

        if ($this->wantsJson()) {
            $user = $this->getAuthUser();
            $this->respondSuccess([
                'id' => $user->getId(),
                'name' => $user->getNome()
            ]);
            return;
        }

        $this->render('index/dashboard');
    }

    private function extractEmail(): string
    {
        return $this->extractFromRequest('email');
    }

    private function extractPassword(): string
    {
        return $this->extractFromRequest('password');
    }

    private function extractName(): string
    {
        return $this->extractFromRequest('name');
    }
}