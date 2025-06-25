@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white text-center">
        <h1 class="h3 mb-0">Termos de Utilização</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="mb-4">
                    <h2 class="h5 text-primary">1. Aceitação dos Termos</h2>
                    <p>Ao aceder e utilizar o TeamApostas, aceita estar vinculado a estes Termos de Utilização. Se não concordar com qualquer parte destes termos, não deve utilizar o nosso serviço.</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">2. Descrição do Serviço</h2>
                    <p>O TeamApostas é uma plataforma de apostas desportivas e jogos de azar que permite aos utilizadores:</p>
                    <ul>
                        <li>Criar e participar em grupos de apostas</li>
                        <li>Fazer apostas em jogos desportivos</li>
                        <li>Participar em jogos de azar como roleta, dados e crash</li>
                        <li>Gerir carteira virtual</li>
                        <li>Interagir com outros utilizadores através do chat</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">3. Elegibilidade</h2>
                    <p>Para utilizar os nossos serviços, deve:</p>
                    <ul>
                        <li>Ter pelo menos 18 anos de idade</li>
                        <li>Ter capacidade legal para celebrar contratos</li>
                        <li>Não estar em território onde apostas online são proibidas</li>
                        <li>Fornecer informações verdadeiras e precisas durante o registo</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">4. Conta do Utilizador</h2>
                    <p>É responsável por:</p>
                    <ul>
                        <li>Manter a confidencialidade das suas credenciais de login</li>
                        <li>Todas as atividades que ocorrem na sua conta</li>
                        <li>Notificar imediatamente sobre utilização não autorizada</li>
                        <li>Manter as suas informações de contacto atualizadas</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">5. Regras de Apostas</h2>
                    <p>As seguintes regras aplicam-se a todas as apostas:</p>
                    <ul>
                        <li>As apostas são finais e não podem ser canceladas após confirmação</li>
                        <li>Os resultados são determinados pelos dados oficiais das competições</li>
                        <li>O TeamApostas reserva-se o direito de anular apostas em caso de irregularidades</li>
                        <li>Limites de apostas podem ser aplicados conforme necessário</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">6. Carteira Virtual</h2>
                    <p>A nossa carteira virtual funciona da seguinte forma:</p>
                    <ul>
                        <li>Os fundos são virtuais e não têm valor real</li>
                        <li>Depósitos e levantamentos são processados conforme disponibilidade</li>
                        <li>Podemos impor limites de transação por motivos de segurança</li>
                        <li>Fundos não utilizados podem ser perdidos após período de inatividade</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">7. Conduta do Utilizador</h2>
                    <p>Concorda em não:</p>
                    <ul>
                        <li>Utilizar o serviço para atividades ilegais</li>
                        <li>Assediar, intimidar ou prejudicar outros utilizadores</li>
                        <li>Tentar hackear ou comprometer a segurança da plataforma</li>
                        <li>Utilizar bots ou scripts automatizados</li>
                        <li>Partilhar conteúdo ofensivo ou inadequado</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">8. Propriedade Intelectual</h2>
                    <p>Todos os direitos de propriedade intelectual relacionados ao TeamApostas, incluindo mas não se limitando a software, design, conteúdo e marcas registadas, pertencem ao TeamApostas ou aos seus licenciadores.</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">9. Limitação de Responsabilidade</h2>
                    <p>O TeamApostas não será responsável por:</p>
                    <ul>
                        <li>Perdas financeiras decorrentes de apostas</li>
                        <li>Interrupções temporárias do serviço</li>
                        <li>Danos indiretos ou consequenciais</li>
                        <li>Ações de terceiros ou outros utilizadores</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">10. Modificações dos Termos</h2>
                    <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação. A utilização continuada do serviço constitui aceitação dos novos termos.</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">11. Rescisão</h2>
                    <p>Podemos suspender ou encerrar a sua conta a qualquer momento por violação destes termos ou por qualquer outro motivo ao nosso critério. Também pode encerrar a sua conta a qualquer momento.</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">12. Lei Aplicável</h2>
                    <p>Estes termos são regidos pelas leis de Portugal. Qualquer disputa será resolvida nos tribunais competentes de Portugal.</p>
                </div>

                <div class="mb-4">
                    <h2 class="h5 text-primary">13. Contacto</h2>
                    <p>Para dúvidas sobre estes termos, contacte-nos através do suporte da plataforma.</p>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">Última atualização: {{ date('d/m/Y') }}</p>
                    <a href="{{ route('legal.privacy') }}" class="btn btn-outline-primary">Política de Privacidade</a>
                    <a href="/" class="btn btn-primary">Voltar ao Início</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 