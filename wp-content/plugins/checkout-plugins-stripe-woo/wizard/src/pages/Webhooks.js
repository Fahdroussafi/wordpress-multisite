import React, { Fragment, useState } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import Spinner from '@Admin/components/Spinner';
import { Listbox, Transition } from '@headlessui/react';
import { useNavigate } from 'react-router-dom';

function Webhooks() {
	const [ clicked, setClicked ] = useState( false );
	const [ webhooks, setWebhooks ] = useState( onboarding_vars.get_webhook_secret );
	const modeOptions = [
		{ id: 'test', name: __( 'Test', 'cart-plugin' ) },
		{ id: 'live', name: __( 'Live', 'cart-plugin' ) },
	];
	const dbValue = Object.keys( modeOptions ).find(
		( key ) =>
			modeOptions[ key ].id === onboarding_vars.get_payment_mode,
	);
	const [ selected, setSelected ] = useState( modeOptions[ dbValue ] );

	const navigate = useNavigate();

	function handleChange( event ) {
		setWebhooks( event.target.value );
	}

	function enableWebhooks() {
		const formData = new window.FormData();

		formData.append(
			'action',
			'cpsw_onboarding_enable_webhooks',
		);
		const webhookKey = document.getElementById( 'cpsw_webhook_secret' ).value;
		const cpswMode = document.getElementById( 'cpsw_mode' ).value;

		if ( '' === webhookKey ) {
			window.alert( __( 'Webhook Secret field is Required.', 'checkout-plugins-stripe-woo' ) );
			return false;
		}

		setClicked( true );
		formData.append( 'webhook_secret', webhookKey );
		formData.append( 'cpsw_mode', cpswMode );
		formData.append(
			'security',
			onboarding_vars.cpsw_onboarding_enable_webhooks,
		);

		apiFetch( {
			url: onboarding_vars.ajax_url,
			method: 'POST',
			body: formData,
		} ).then( ( res ) => {
			if ( res.success ) {
				navigate(
					onboarding_vars.navigator_base + `&cpsw_call=thank-you`,
				);
			}
		} );
	}

	return (
		<main className="mt-4 mb-4 mx-auto w-auto max-w-7xl px-4 sm:mt-6 sm:px-6 md:mt-8 lg:mt-10 lg:px-8 xl:mt-16">
			<div className="text-center">
				<h1 className="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
					<span className="block text-cart-500 xl:inline">{ __( 'This is important!!', 'checkout-plugins-stripe-woo' ) }</span>
				</h1>
				<h3 className="text-2xl tracking-tight mt-5 font-bold text-gray-700">
					<span className="block xl:inline">{ __( 'Enable Webhooks', 'checkout-plugins-stripe-woo' ) }</span>
				</h3>
				<p className="mt-2 text-sm mb-5 text-gray-500 sm:mt-2 sm:text-xl sm:mx-auto md:mt-2 md:text-xl lg:mx-0">
					<span className="block xl:inline font-bold select-none text-gray-700">{ __( 'Webhook URL', 'checkout-plugins-stripe-woo' ) }: </span> <span className="block xl:inline font-bold select-text">{ onboarding_vars.webhook_url }</span>
				</p>
				<div className="block mx-auto mt-2 mb-5 w-full md:w-6/12 lg:w-6/12">
					<p className="mt-2 text-sm text-gray-400 sm:mt-2 sm:text-sm sm:mx-auto md:mt-2 md:text-md lg:mx-0">
						<span className="block"> { __( 'The webhook URL is called by', 'checkout-plugins-stripe-woo' ) } <span className="block text-gray-700 xl:inline font-bold">{ __( 'Stripe', 'checkout-plugins-stripe-woo' ) },</span>{ __( ' when events occur in your account, like a source becomes chargeable.', 'checkout-plugins-stripe-woo' ) } <a href="https://checkoutplugins.com/docs/stripe-card-payments/#webhook" target="_blank" rel="noreferrer"><span className="block xl:inline font-bold text-cart-500">{ __( 'Webhook Guide', 'checkout-plugins-stripe-woo' ) }</span></a> { __( 'or create webhook secret on', 'checkout-plugins-stripe-woo' ) } <a href="https://dashboard.stripe.com/webhooks/create" target="_blank" rel="noreferrer"><span className="block text-cart-500 xl:inline font-bold">{ __( 'Stripe Dashboard', 'checkout-plugins-stripe-woo' ) }</span></a>.</span>
					</p>

					<p className="text-left mt-3 text-sm text-gray-400 sm:mt-5 sm:text-sm sm:mx-auto md:mt-5 md:text-md lg:mx-0">
						<span className="block text-gray-700 xl:inline font-bold"> { __( 'This is the list of the supported webhook events: ', 'checkout-plugins-stripe-woo' ) } </span>
					</p>
					<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-1 text-left">
						<div><ul className="list-disc mt-3 pl-4 text-sm text-gray-400"><li>charge.captured</li><li>charge.refunded</li><li>charge.dispute.created</li><li>charge.dispute.closed</li><li>payment_intent.succeeded</li></ul></div>
						<div><ul className="list-disc md:mt-3 lg:mt-3 pl-4 text-sm text-gray-400"><li>payment_intent.amount_capturable_updated</li><li>payment_intent.payment_failed</li><li>review.opened</li><li>review.closed</li></ul></div>
					</div>
				</div>
				<div className="block mx-auto mt-5 mb-5 w-full md:w-6/12 lg:w-6/12">
					<span className="block text-gray-700 font-bold text-left">{ __( 'Select Mode', 'checkout-plugins-stripe-woo' ) }</span>
					<Listbox value={ selected } onChange={ setSelected }>
						<div className="relative mt-1">
							<Listbox.Button className="relative w-full py-2 pl-3 pr-10 h-12 text-left bg-white rounded appearance-none border shadow cursor-default focus:outline-none focus-visible:ring-2 focus-visible:ring-opacity-75 focus-visible:ring-white focus-visible:ring-offset-orange-300 focus-visible:ring-offset-2 focus-visible:border-indigo-500 sm:text-sm">
								<span className="block truncate">
									{ selected.name }
								</span>
								<span className="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
									<span className="dashicons dashicons-arrow-down-alt2"></span>
								</span>
							</Listbox.Button>
							<Transition
								as={ Fragment }
								leave="transition ease-in duration-100"
								leaveFrom="opacity-100"
								leaveTo="opacity-0"
							>
								<Listbox.Options className="absolute w-full py-1 mt-1 z-40 text-left overflow-auto text-base bg-white rounded border shadow max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
									{ modeOptions.map( ( options, id ) => (
										<Listbox.Option
											key={ id }
											className={ ( { active } ) =>
												`${
													active
														? ' text-gray-500 bg-wpcolor'
														: 'text-gray-900'
												}
								cursor-default select-none relative py-1 pl-4`
											}
											value={ options }
										>
											{ ( { active } ) => (
												<>
													<span
														className={ `${
															selected
																? 'font-medium'
																: 'font-normal'
														} block` }
													>
														{ options.name }
													</span>
													{ selected ? (
														<span
															className={ `${
																active
																	? 'text-wpcolor'
																	: 'text-wpcolor20'
															}
										absolute inset-y-0 left-0 flex items-center pl-3` }
														></span>
													) : null }
												</>
											) }
										</Listbox.Option>
									) ) }
								</Listbox.Options>
							</Transition>
						</div>
					</Listbox>
					<input
						type="hidden"
						name="cpsw_mode"
						id="cpsw_mode"
						value={ selected.id }
					/>
				</div>
				<div className="w-full md:w-6/12 lg:w-6/12 block mx-auto mt-5 mb-5">
					<span className="block text-gray-700 font-bold text-left">{ selected.name } { __( 'Webhook Secret', 'checkout-plugins-stripe-woo' ) }</span>
					<p className="mt-2 text-sm text-gray-400 sm:mt-2 sm:text-sm sm:mx-auto md:mt-2 md:text-md lg:mx-0">
						<input type="text" value={ webhooks } onChange={ handleChange } name="webhook_secret" id="cpsw_webhook_secret" placeholder={ __( 'Enter key here', 'checkout-plugins-stripe-woo' ) } className="w-full shadow appearance-none border rounded h-12 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
						<span className="block mt-3"> { __( 'The webhook secret is used to authenticate webhooks sent from Stripe. It ensures nobody else can send you events pretending to be Stripe', 'checkout-plugins-stripe-woo' ) }.</span>
					</p>
				</div>
				<div className="sm:inline-block lg:inline-block sm:justify-center lg:justify-center">
					<div className="rounded-md shadow">
						{ clicked ? (
							<button className="disabled w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-cart-500 hover:bg-cart-700 md:py-4 md:text-lg md:px-10 cursor-wait">
								{ __( 'Saving…', 'checkout-plugins-stripe-woo' ) }
								<Spinner />
							</button>
						) : (
							<button onClick={ enableWebhooks } className="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-cart-500 hover:bg-cart-700 md:py-4 md:text-lg md:px-10 cursor-pointer">
								{ __( 'Save & Continue', 'checkout-plugins-stripe-woo' ) }
							</button> )
						}
					</div>

					<div className="mt-3 sm:mt-0">
						<a href={ onboarding_vars.base_url + `&cpsw_call=thank-you` } className="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-slate-300 md:py-4 md:text-lg md:px-10">
							{ __( 'Skip', 'checkout-plugins-stripe-woo' ) }
						</a>
					</div>
				</div>
			</div>
		</main>
	);
}

export default Webhooks;
